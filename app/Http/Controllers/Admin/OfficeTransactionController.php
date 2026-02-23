<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeAccount;
use App\Models\OfficeTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficeTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant');
    }

    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = OfficeTransaction::with(['fromAccount', 'toAccount', 'creator']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($type = $request->get('transaction_type')) {
            $query->where('transaction_type', $type);
        }

        if ($accountId = $request->get('account_id')) {
            $query->where(function ($q) use ($accountId) {
                $q->where('from_account_id', $accountId)
                    ->orWhere('to_account_id', $accountId);
            });
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();
        $balances = OfficeAccount::pluck('remaining_balance', 'id')->map(fn($value) => (float) $value)->all();

        foreach ($transactions as $transaction) {
            $fromId = $transaction->from_account_id;
            $toId = $transaction->to_account_id;
            $amount = (float) $transaction->amount;

            $transaction->from_balance_after = $fromId ? ($balances[$fromId] ?? null) : null;
            $transaction->to_balance_after = $toId ? ($balances[$toId] ?? null) : null;

            // Move balances one step back so next (older) row gets its own "after transaction" value.
            if ($fromId && array_key_exists($fromId, $balances)) {
                $balances[$fromId] += $amount;
            }
            if ($toId && array_key_exists($toId, $balances)) {
                $balances[$toId] -= $amount;
            }
        }

        $accounts = OfficeAccount::where('status', 'active')->get();

        return view('admin.office-transactions.index', compact('transactions', 'accounts'));
    }

    public function create()
    {
        $this->authorize('*accountant');
        $accounts = OfficeAccount::where('status', 'active')->get();
        return view('admin.office-transactions.create', compact('accounts'));
    }

    public function show(OfficeTransaction $officeTransaction)
    {
        $this->authorize('*accountant');
        $officeTransaction->load(['fromAccount', 'toAccount', 'creator']);
        return view('admin.office-transactions.show', compact('officeTransaction'));
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'from_account_id' => ['nullable', 'required_if:transaction_type,transfer,withdrawal', 'exists:office_accounts,id'],
            'to_account_id' => ['nullable', 'required_if:transaction_type,transfer,deposit', 'exists:office_accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
            'transaction_type' => ['required', Rule::in(['transfer', 'deposit', 'withdrawal'])],
            'reference' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($validated['transaction_type'] === 'transfer' && $validated['from_account_id'] == $validated['to_account_id']) {
            return back()->withErrors(['to_account_id' => 'Sender and receiver accounts must be different.'])->withInput();
        }

        OfficeTransaction::create($validated);

        return redirect()
            ->route('admin.office-transactions.index')
            ->with('success', 'Transaction recorded successfully.');
    }

    public function destroy(OfficeTransaction $officeTransaction)
    {
        $this->authorize('*accountant');
        $officeTransaction->delete();

        return redirect()
            ->route('admin.office-transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}
