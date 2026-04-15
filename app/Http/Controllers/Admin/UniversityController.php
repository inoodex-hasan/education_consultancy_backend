<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Country, University};

class UniversityController extends Controller
{

    public function index(Request $request)
    {

        $query = University::with('country');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($countryId = $request->get('country_id')) {
            $query->where('country_id', $countryId);
        }

        $universities = $query->latest()->paginate(15)->withQueryString();
        $countries = Country::where('status', 1)->orderBy('name')->get();

        return view('admin.universities.index', compact('universities', 'countries'));
    }

    public function create()
    {

        $countries = Country::where('status', 1)->orderBy('name')->get();

        return view('admin.universities.create', compact('countries'));
    }

    public function store(Request $request)
    {

        $validated = $this->validateUniversity($request);

        University::create($validated);

        return redirect()
            ->route('admin.universities.index')
            ->with('success', 'University created successfully.');
    }

    public function edit(University $university)
    {

        $countries = Country::where('status', 1)->orderBy('name')->get();

        return view('admin.universities.edit', compact('university', 'countries'));
    }

    public function update(Request $request, University $university)
    {

        $validated = $this->validateUniversity($request);

        $university->update($validated);

        return redirect()
            ->route('admin.universities.index')
            ->with('success', 'University updated successfully.');
    }

    public function destroy(University $university)
    {

        $university->delete();

        return redirect()
            ->route('admin.universities.index')
            ->with('success', 'University deleted successfully.');
    }

    private function validateUniversity(Request $request): array
    {
        return $request->validate([
            'country_id' => ['required', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);
    }
}
