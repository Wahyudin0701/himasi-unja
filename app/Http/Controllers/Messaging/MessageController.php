<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Messaging\Channel;
use App\Models\Messaging\Message;
use App\Models\Messaging\MessageRead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Daftar semua channel yang bisa diakses user.
     */
    public function index()
    {
        $user = Auth::user();

        $channels = Channel::whereHas('members', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['latestMessage.sender', 'members'])
        ->get()
        ->map(function ($channel) use ($user) {
            $channel->unread_count = $channel->unreadCountFor($user->id);
            return $channel;
        })
        ->sortByDesc(function ($channel) {
            return $channel->latestMessage?->created_at ?? $channel->created_at;
        })
        ->values();

        return view('messaging.index', compact('channels'));
    }

    /**
     * Tampilkan percakapan dalam channel.
     */
    public function show($channelId)
    {
        $user = Auth::user();

        $channel = Channel::whereHas('members', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($channelId);

        $messages = $channel->messages()
            ->with(['sender', 'reads'])
            ->orderBy('created_at', 'asc')
            ->get();

        $members = $channel->members;

        // Tandai semua pesan sebagai dibaca
        $unreadMessageIds = $messages
            ->where('sender_id', '!=', $user->id)
            ->filter(function ($msg) use ($user) {
                return !$msg->isReadBy($user->id);
            })
            ->pluck('id');

        if ($unreadMessageIds->isNotEmpty()) {
            $reads = $unreadMessageIds->map(function ($msgId) use ($user) {
                return [
                    'message_id' => $msgId,
                    'user_id' => $user->id,
                    'read_at' => now(),
                ];
            })->toArray();

            MessageRead::insert($reads);
        }

        return view('messaging.show', compact('channel', 'messages', 'members'));
    }

    /**
     * Kirim pesan baru.
     */
    public function store(Request $request, $channelId)
    {
        $user = Auth::user();

        $channel = Channel::whereHas('members', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($channelId);

        $request->validate([
            'body' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        if (!$request->body && !$request->hasFile('attachment')) {
            return back()->with('error', 'Pesan tidak boleh kosong.');
        }

        $data = [
            'channel_id' => $channel->id,
            'sender_id' => $user->id,
            'body' => $request->body,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments/messages', 'public');
            $data['attachment_path'] = $path;
            $data['attachment_name'] = $file->getClientOriginalName();
        }

        Message::create($data);

        return back();
    }

    /**
     * Download lampiran file.
     */
    public function download($messageId)
    {
        $user = Auth::user();

        $message = Message::findOrFail($messageId);

        // Pastikan user adalah anggota channel
        $isMember = $message->channel->members()->where('user_id', $user->id)->exists();
        abort_unless($isMember, 403);

        abort_unless($message->attachment_path, 404);

        return Storage::disk('public')->download(
            $message->attachment_path,
            $message->attachment_name
        );
    }
}
