<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorWithdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $tutor = $request->user()->tutor;

        if (!$tutor) {
            return redirect()->route('landing')->with('error', 'Profil tutor tidak ditemukan.');
        }

        $withdrawals = TutorWithdrawal::where('tutor_id', $tutor->id)
            ->latest()
            ->paginate(20);

        return view('tutor.withdrawals', compact('tutor', 'withdrawals'));
    }

    public function create(Request $request)
    {
        $tutor = $request->user()->tutor;

        if (!$tutor) {
            return redirect()->route('landing')->with('error', 'Profil tutor tidak ditemukan.');
        }

        // Check if bank account is set
        if (!$tutor->bank_account_number) {
            return redirect()->route('tutor.profile.edit')
                ->with('error', 'Silakan lengkapi informasi rekening bank terlebih dahulu.');
        }

        return view('tutor.withdrawal-create', compact('tutor'));
    }

    public function store(Request $request)
    {
        $tutor = $request->user()->tutor;

        if (!$tutor) {
            return redirect()->route('landing')->with('error', 'Profil tutor tidak ditemukan.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:50000|max:' . $tutor->wallet_available,
        ], [
            'amount.max' => 'Jumlah penarikan tidak boleh melebihi saldo tersedia.',
        ]);

        // Deduct from available balance
        $tutor->decrement('wallet_available', $request->amount);

        TutorWithdrawal::create([
            'tutor_id' => $tutor->id,
            'amount' => $request->amount,
            'bank_name' => $tutor->bank_name,
            'bank_account_number' => $tutor->bank_account_number,
            'bank_account_holder' => $tutor->bank_account_holder,
            'status' => 'pending',
        ]);

        return redirect()->route('tutor.withdrawals.index')
            ->with('success', 'Permintaan penarikan berhasil diajukan. Menunggu persetujuan admin.');
    }
}
