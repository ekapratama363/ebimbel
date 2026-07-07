<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        return view('pages.pengaturan', [
            'setting' => SiteSetting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = SiteSetting::current();

        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'admin_brand' => 'required|string|max:255',
            'landing_title' => 'required|string|max:255',
            'meta_description' => 'nullable|string',
            'organization_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('site', 'public');
            $data['logo_path'] = 'storage/'.$path;
        }

        unset($data['logo']);
        $setting->update($data);
        SiteSetting::refreshCache();

        return back()->with('status', 'Pengaturan situs berhasil disimpan.');
    }
}
