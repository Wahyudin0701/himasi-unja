<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\FinanceTransaction;
use App\Models\Kepengurusan\Period;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FinanceTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();

        // Get filter inputs
        $month = $request->input('month', ''); // Default kosong = semua bulan

        // Query transactions
        $query = FinanceTransaction::where('period_id', $activePeriod->id);
        
        if ($month) {
            $query->whereMonth('date', $month);
        }
        
        $query->latest('date')->latest('id');

        $transactions = $query->paginate(15)->withQueryString();

        // Calculate summaries for this period up to the selected month/year (or total)
        // Usually, saldo is calculated up to the current date or selected period
        // Let's calculate total pemasukan and pengeluaran for the active period
        $allTransactions = FinanceTransaction::where('period_id', $activePeriod->id)->get();
        
        $totalPemasukan = $allTransactions->where('type', 'pemasukan')->sum('amount');
        $totalPengeluaran = $allTransactions->where('type', 'pengeluaran')->sum('amount');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Summaries for the selected month/period
        $monthPemasukan = $allTransactions->where('type', 'pemasukan')
            ->filter(function($t) use ($month) {
                if ($month) {
                    return $t->date->format('m') == $month;
                }
                return true; // Jika bulan tidak dipilih, ambil seluruh periode
            })->sum('amount');
            
        $monthPengeluaran = $allTransactions->where('type', 'pengeluaran')
            ->filter(function($t) use ($month) {
                if ($month) {
                    return $t->date->format('m') == $month;
                }
                return true;
            })->sum('amount');

        return view('kepengurusan.bendahara.finances.index', compact(
            'transactions', 'totalPemasukan', 'totalPengeluaran', 'saldo', 
            'monthPemasukan', 'monthPengeluaran', 'month', 'activePeriod'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();

        $request->validate([
            'type'        => 'required|in:pemasukan,pengeluaran',
            'amount'      => 'required|numeric|min:0',
            'category'    => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'proof_file'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only(['type', 'amount', 'category', 'description', 'date']);
        $data['period_id'] = $activePeriod->id;
        $data['user_id'] = Auth::id();

        if ($request->hasFile('proof_file')) {
            $data['proof_file'] = $request->file('proof_file')->store('finance-proofs', 'public');
        }

        FinanceTransaction::create($data);

        return back()->with('success', 'Transaksi berhasil dicatat.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinanceTransaction $finance)
    {
        $request->validate([
            'type'        => 'required|in:pemasukan,pengeluaran',
            'amount'      => 'required|numeric|min:0',
            'category'    => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'proof_file'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only(['type', 'amount', 'category', 'description', 'date']);

        if ($request->hasFile('proof_file')) {
            if ($finance->proof_file) {
                Storage::disk('public')->delete($finance->proof_file);
            }
            $data['proof_file'] = $request->file('proof_file')->store('finance-proofs', 'public');
        }

        $finance->update($data);

        return back()->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceTransaction $finance)
    {
        if ($finance->proof_file) {
            Storage::disk('public')->delete($finance->proof_file);
        }
        $finance->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
