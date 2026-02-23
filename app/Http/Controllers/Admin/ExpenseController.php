<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\{Expense, FinanceCategory, OfficeAccount, OfficeTransaction, Salary};
use Barryvdh\DomPDF\Facade\Pdf;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant');
    }

    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = Expense::with(['creator']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $expenses = $query->latest()->paginate(15)->withQueryString();

        // Fetch unique categories for the filter
        $categories = FinanceCategory::where('is_active', true)
            ->whereIn('type', ['expense', 'both'])
            ->get();

        return view('admin.expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $this->authorize('*accountant');
        $categories = FinanceCategory::where('is_active', true)
            ->whereIn('type', ['expense', 'both'])
            ->get();
        $accounts = OfficeAccount::where('status', 'active')->get();
        $pendings_salaries = Salary::whereIn('payment_status', ['pending', 'partial'])->get();
        return view('admin.expenses.create', compact('categories', 'accounts', 'pendings_salaries'));
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $this->validateExpense($request);

        $expense = Expense::create($validated);

        // Update salary payment status if linked
        if ($expense->salary_id) {
            $salary = Salary::find($expense->salary_id);
            if ($salary) {
                $totalPaid = Expense::where('salary_id', $expense->salary_id)->sum('amount');
                $salary->paid_amount = $totalPaid;
                
                if ($totalPaid >= $salary->net_salary) {
                    $salary->payment_status = 'paid';
                } elseif ($totalPaid > 0) {
                    $salary->payment_status = 'partial';
                }
                $salary->save();
            }
        }

        // Auto-log as office transaction
        if ($expense->office_account_id) {
            OfficeTransaction::create([
                'from_account_id' => $expense->office_account_id,
                'to_account_id' => null,
                'amount' => $expense->amount,
                'transaction_date' => $expense->expense_date,
                'transaction_type' => 'expense',
                'reference' => 'Expense: ' . $expense->description,
                'notes' => $expense->notes,
            ]);
        }

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $this->authorize('*accountant');
        $categories = FinanceCategory::where('is_active', true)
            ->whereIn('type', ['expense', 'both'])
            ->get();
        $accounts = OfficeAccount::where('status', 'active')->get();
        return view('admin.expenses.edit', compact('expense', 'categories', 'accounts'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('*accountant');

        $validated = $this->validateExpense($request);

        $expense->update($validated);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('*accountant');

        $expense->delete();

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    public function downloadPdf(Expense $expense)
    {
        $this->authorize('*accountant');

        $pdf = Pdf::loadView('admin.expenses.pdf', compact('expense'));
        return $pdf->download('expense-' . $expense->id . '.pdf');
    }

    private function validateExpense(Request $request): array
    {
        return $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'category' => ['required', 'string', 'exists:finance_categories,name'],
            'payment_method' => ['required', 'in:cash,bank_transfer,mobile_banking'],
            'office_account_id' => ['nullable', 'exists:office_accounts,id'],
            'salary_id' => ['nullable', 'exists:salaries,id'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
