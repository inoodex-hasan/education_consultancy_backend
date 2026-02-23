<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-settings');
    }

    public function index()
    {
        $this->authorize('manage-settings');

        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->authorize('manage-settings');

        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'app_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'currency_symbol' => 'nullable|string|max:10',
            'date_format' => 'nullable|string|max:50',
            'enable_registration' => 'nullable|boolean',
            'maintenance_mode' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'commission_marketing_percent' => 'nullable|numeric|min:0|max:100',
            'commission_consultant_percent' => 'nullable|numeric|min:0|max:100',
            'commission_application_percent' => 'nullable|numeric|min:0|max:100',
            'commission_accountant_percent' => 'nullable|numeric|min:0|max:100',
            'employee_commissions' => 'nullable|array',
            'employee_commissions.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $keys = [
            'app_name',
            'contact_email',
            'contact_phone',
            'address',
            'social_facebook',
            'social_twitter',
            'social_linkedin',
            'currency_symbol',
            'date_format',
            'enable_registration',
            'maintenance_mode',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'commission_marketing_percent',
            'commission_consultant_percent',
            'commission_application_percent',
            'commission_accountant_percent',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
            }
            else {
                // Handle checkboxes that are unchecked (not sent in request)
                if (in_array($key, ['enable_registration', 'maintenance_mode'])) {
                    Setting::updateOrCreate(['key' => $key], ['value' => '0']);
                }
            }
        }

        if ($request->hasFile('app_logo')) {
            $logo = $request->file('app_logo');
            $logoPath = $logo->store('uploads/settings', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $logoPath]);
        }

        if ($request->hasFile('app_favicon')) {
            $favicon = $request->file('app_favicon');
            $faviconPath = $favicon->store('uploads/settings', 'public');
            Setting::updateOrCreate(['key' => 'app_favicon'], ['value' => $faviconPath]);
        }

        // Update individual marketing employee commissions
        if ($request->has('employee_commissions')) {
            foreach ($request->input('employee_commissions') as $userId => $commission) {
                \App\Models\User::where('id', $userId)->update(['commission_percentage' => $commission]);
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
