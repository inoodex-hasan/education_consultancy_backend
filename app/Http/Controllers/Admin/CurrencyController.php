<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant|*consultant|*application');
    }

    public function index()
    {
        $currencies = Currency::latest()->get();
        return view('admin.currencies.index', compact('currencies'));
    }

    public function refresh(\App\Services\CurrencyService $currencyService)
    {
        $result = $currencyService->updateRates();
        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }
        return redirect()->back()->with('error', $result['message']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:currencies,code',
            'symbol' => 'nullable|string|max:10',
            'is_active' => 'required|boolean',
        ]);

        Currency::create($validated);

        return redirect()->back()->with('success', 'Currency added successfully.');
    }

    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:currencies,code,' . $currency->id,
            'symbol' => 'nullable|string|max:10',
            'is_active' => 'required|boolean',
        ]);

        $currency->update($validated);

        return redirect()->back()->with('success', 'Currency updated successfully.');
    }

    public function destroy(Currency $currency)
    {
        if (\App\Models\Course::where('currency', $currency->code)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete currency because it is being used by courses.');
        }

        $currency->delete();

        return redirect()->back()->with('success', 'Currency deleted successfully.');
    }
}
