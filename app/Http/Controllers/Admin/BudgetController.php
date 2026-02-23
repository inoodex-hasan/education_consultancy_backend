<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\FinanceCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant');
    }

    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = Budget::with(['creator']);

        if ($search = $request->get('search')) {
            $query->where('category', 'like', "%{$search}%");
        }

        if ($period = $request->get('period')) {
            $query->where('period', $period);
        }

        $budgets = $query->latest()->paginate(15)->withQueryString();

        return view('admin.budgets.index', compact('budgets'));
    }

    public function create()
    {
        $this->authorize('*accountant');
        $categories = FinanceCategory::where('is_active', true)
            ->whereIn('type', ['expense', 'both'])
            ->get();
        return view('admin.budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $this->validateBudget($request);

        Budget::create($validated);

        return redirect()
            ->route('admin.budgets.index')
            ->with('success', 'Budget allocated successfully.');
    }

    public function edit(Budget $budget)
    {
        $this->authorize('*accountant');
        $categories = FinanceCategory::where('is_active', true)
            ->whereIn('type', ['expense', 'both'])
            ->get();
        return view('admin.budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorize('*accountant');

        $validated = $this->validateBudget($request);

        $budget->update($validated);

        return redirect()
            ->route('admin.budgets.index')
            ->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('*accountant');
        $budget->delete();

        return redirect()
            ->route('admin.budgets.index')
            ->with('success', 'Budget deleted successfully.');
    }

    private function validateBudget(Request $request): array
    {
        return $request->validate([
            'category' => ['required', 'string', 'exists:finance_categories,name'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'period' => ['required', Rule::in(['monthly', 'yearly'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
