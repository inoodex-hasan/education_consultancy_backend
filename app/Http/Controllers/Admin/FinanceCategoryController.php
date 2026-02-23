<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinanceCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant');
    }

    public function index()
    {
        $categories = FinanceCategory::latest()->get();
        return view('admin.finance-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:finance_categories'],
            'type' => ['required', Rule::in(['expense', 'income', 'both'])],
        ]);

        FinanceCategory::create($validated);

        return redirect()->back()->with('success', 'Category added successfully.');
    }

    public function update(Request $request, FinanceCategory $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('finance_categories')->ignore($category->id)],
            'type' => ['required', Rule::in(['expense', 'income', 'both'])],
            'is_active' => ['boolean'],
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy(FinanceCategory $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
