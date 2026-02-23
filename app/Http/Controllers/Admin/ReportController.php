<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\OfficeTransaction;
use App\Models\Budget;
use App\Models\Setting;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant');
    }

    public function summary(Request $request)
    {
        $this->authorize('*accountant');

        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $accountId = $request->get('account_id');
        $transactionType = $request->get('transaction_type'); // 'income', 'expense', 'transfer'

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $accounts = \App\Models\OfficeAccount::orderBy('account_name')->get();

        $expensesQuery = Expense::whereBetween('expense_date', [$startDate, $endDate]);
        $paymentsQuery = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->whereIn('payment_status', ['pending', 'completed']);
        $transfersQuery = OfficeTransaction::where('transaction_type', 'transfer')
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        // Filter by Account
        if ($accountId) {
            $expensesQuery->where('office_account_id', $accountId);
            $paymentsQuery->where('office_account_id', $accountId);
            $transfersQuery->where(function ($q) use ($accountId) {
                $q->where('from_account_id', $accountId)->orWhere('to_account_id', $accountId);
            });
        }

        // Filter by Transaction Type
        if ($transactionType) {
            if ($transactionType !== 'expense') {
                $expensesQuery->whereRaw('1 = 0'); // Or alternative to always return empty
            }
            if ($transactionType !== 'income') {
                $paymentsQuery->whereRaw('1 = 0');
            }
            if ($transactionType !== 'transfer') {
                $transfersQuery->whereRaw('1 = 0');
            }
        }

        $expenses = $expensesQuery->get();
        $payments = $paymentsQuery->get();
        $transfers = $transfersQuery->get();

        $budgets = Budget::where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->get();

        $summary = [
            'total_income' => $payments->sum('amount'),
            'total_expense' => $expenses->sum('amount'),
            'total_transfer' => $transfers->sum('amount'),
            'income_count' => $payments->count(),
            'expense_count' => $expenses->count(),
            'transfer_count' => $transfers->count(),
            'by_category' => $expenses->groupBy('category')->map->sum('amount'),
            'by_method' => $expenses->groupBy('payment_method')->map->sum('amount'),
        ];

        return view('admin.reports.summary', compact('summary', 'month', 'year', 'expenses', 'transfers', 'budgets', 'payments', 'accounts', 'accountId', 'transactionType'));
    }

    public function downloadPdf(Request $request)
    {
        $this->authorize('*accountant');

        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $accountId = $request->get('account_id');
        $transactionType = $request->get('transaction_type');

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $expensesQuery = Expense::with(['creator'])->whereBetween('expense_date', [$startDate, $endDate]);
        $paymentsQuery = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->whereIn('payment_status', ['pending', 'completed']);
        $transfersQuery = OfficeTransaction::with(['fromAccount', 'toAccount'])->where('transaction_type', 'transfer')
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        // Filter by Account
        if ($accountId) {
            $expensesQuery->where('office_account_id', $accountId);
            $paymentsQuery->where('office_account_id', $accountId);
            $transfersQuery->where(function ($q) use ($accountId) {
                $q->where('from_account_id', $accountId)->orWhere('to_account_id', $accountId);
            });
        }

        // Filter by Transaction Type
        if ($transactionType) {
            if ($transactionType !== 'expense') {
                $expensesQuery->whereRaw('1 = 0');
            }
            if ($transactionType !== 'income') {
                $paymentsQuery->whereRaw('1 = 0');
            }
            if ($transactionType !== 'transfer') {
                $transfersQuery->whereRaw('1 = 0');
            }
        }

        $expenses = $expensesQuery->get();
        $payments = $paymentsQuery->get();
        $transfers = $transfersQuery->get();

        $settings = Setting::pluck('value', 'key')->all();
        $reportDate = $startDate->format('F Y');

        $pdf = Pdf::loadView('admin.reports.pdf', compact('expenses', 'payments', 'transfers', 'settings', 'reportDate'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Financial_Report_{$reportDate}.pdf");
    }
}
