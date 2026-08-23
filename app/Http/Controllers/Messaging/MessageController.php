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

        if ($user->global_role === 'super_admin') {
            $channelsQuery = Channel::query();
        } else {
            $channelsQuery = Channel::whereHas('members', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $channels = $channelsQuery->with(['latestMessage.sender', 'members'])
        ->get()
        ->map(function ($channel) use ($user) {
            $channel->unread_count = $user->global_role === 'super_admin' ? 0 : $channel->unreadCountFor($user->id);
            return $channel;
        })
        ->sortByDesc(function ($channel) {
            return $channel->latestMessage?->created_at ?? $channel->created_at;
        })
        ->values();

        $allUsers = collect();
        $divisions = collect();
        if (in_array($user->global_role, ['super_admin', 'kahim', 'wakahim', 'sekretaris'])) {
            $allUsers = \App\Models\User::with(['memberships' => function($q) {
                $q->whereHas('division.period', function($q2) {
                    $q2->where('is_active', true);
                })->with('division');
            }])->orderBy('name', 'asc')->get();

            $activePeriod = \App\Models\Kepengurusan\Period::where('is_active', true)->first();
            if ($activePeriod) {
                $divisions = \App\Models\Kepengurusan\Division::where('period_id', $activePeriod->id)->get();
            }
        }

        return view('messaging.index', compact('channels', 'allUsers', 'divisions'));
    }

    /**
     * Buat channel baru
     */
    public function storeChannel(Request $request)
    {
        $user = Auth::user();

        // Hanya role tertentu yang boleh membuat channel
        if (!in_array($user->global_role, ['super_admin', 'kahim', 'wakahim', 'sekretaris'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'members' => 'required|array',
            'members.*' => 'exists:users,id',
        ]);

        $activePeriod = \App\Models\Kepengurusan\Period::where('is_active', true)->first();

        $channel = Channel::create([
            'name' => $request->name,
            'type' => $request->type,
            'period_id' => $activePeriod ? $activePeriod->id : null,
        ]);

        // Selalu sertakan pembuat ke dalam channel JIKA ia bukan super_admin
        $members = collect($request->members);
        if ($user->global_role !== 'super_admin' && !$members->contains($user->id)) {
            $members->push($user->id);
        }

        $channel->members()->attach($members);

        if ($user->global_role === 'super_admin') {
            return redirect()->route('messages.index')->with('success', 'Channel pesan berhasil dibuat.');
        }

        return redirect()->route('messages.show', $channel->id)->with('success', 'Channel pesan berhasil dibuat.');
    }

    /**
     * Hapus Channel
     */
    public function destroyChannel($id)
    {
        $user = Auth::user();
        if (!in_array($user->global_role, ['super_admin', 'kahim', 'wakahim', 'sekretaris'])) {
            abort(403);
        }

        $channel = Channel::findOrFail($id);
        $channel->delete();

        return redirect()->back()->with('success', 'Channel berhasil dihapus.');
    }

    /**
     * Update Anggota Channel
     */
    public function updateChannelMembers(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->global_role, ['super_admin', 'kahim', 'wakahim', 'sekretaris'])) {
            abort(403);
        }

        $request->validate([
            'members' => 'required|array',
            'members.*' => 'exists:users,id',
        ]);

        $channel = Channel::findOrFail($id);
        $members = collect($request->members);
        
        if ($user->global_role !== 'super_admin' && !$members->contains($user->id)) {
            $members->push($user->id);
        }

        $channel->members()->sync($members);

        return redirect()->back()->with('success', 'Anggota channel berhasil diperbarui.');
    }

    /**
     * Tampilkan percakapan dalam channel.
     */
    public function show($channelId)
    {
        $user = Auth::user();

        if ($user->global_role === 'super_admin') {
            $channel = Channel::findOrFail($channelId);
        } else {
            $channel = Channel::whereHas('members', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->findOrFail($channelId);
        }

        $messages = $channel->messages()
            ->with(['sender', 'reads'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Exclude super_admin from the member list to maintain God View integrity
        $members = $channel->members->reject(function ($member) {
            return $member->global_role === 'super_admin';
        });

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
