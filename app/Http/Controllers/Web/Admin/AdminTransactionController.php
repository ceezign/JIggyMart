<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['user', 'order'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(20);

        return view('dashboard.admin.transactions', compact('transactions'));
    }
}
