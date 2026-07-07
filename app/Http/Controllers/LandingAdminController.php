<?php

namespace App\Http\Controllers;

use App\Models\LandingItem;
use App\Models\LandingProgram;
use App\Models\LandingSection;
use App\Models\LandingSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingAdminController extends Controller
{
    public function index(): View
    {
        return view('pages.konten-landing', [
            'slides' => LandingSlide::orderBy('sort_order')->get(),
            'programs' => LandingProgram::orderBy('sort_order')->get(),
            'sections' => LandingSection::map(),
            'heroPoints' => LandingItem::where('group', 'hero_point')->orderBy('sort_order')->get(),
            'quickLinks' => LandingItem::where('group', 'quick_link')->orderBy('sort_order')->get(),
            'stats' => LandingItem::where('group', 'stat')->orderBy('sort_order')->get(),
            'aboutValues' => LandingItem::where('group', 'about_value')->orderBy('sort_order')->get(),
            'features' => LandingItem::where('group', 'feature')->orderBy('sort_order')->get(),
            'ortuPoints' => LandingItem::where('group', 'ortu_point')->orderBy('sort_order')->get(),
            'kemitraanPoints' => LandingItem::where('group', 'kemitraan_point')->orderBy('sort_order')->get(),
            'steps' => LandingItem::where('group', 'step')->orderBy('sort_order')->get(),
        ]);
    }

    public function storeSlide(Request $request): RedirectResponse
    {
        $data = $this->validateSlide($request);

        if ($request->hasFile('image')) {
            $data['image_path'] = 'storage/'.$request->file('image')->store('slides', 'public');
        }

        LandingSlide::create($data);

        return back()->with('status', 'Slide banner berhasil ditambahkan.');
    }

    public function updateSlide(Request $request, LandingSlide $slide): RedirectResponse
    {
        $data = $this->validateSlide($request);

        if ($request->hasFile('image')) {
            $slide->deleteImageFile();
            $data['image_path'] = 'storage/'.$request->file('image')->store('slides', 'public');
        }

        $slide->update($data);

        return back()->with('status', 'Slide banner berhasil diperbarui.');
    }

    public function destroySlide(LandingSlide $slide): RedirectResponse
    {
        $slide->deleteImageFile();
        $slide->delete();

        return back()->with('status', 'Slide banner dihapus.');
    }

    public function storeProgram(Request $request): RedirectResponse
    {
        LandingProgram::create($this->validateProgram($request));

        return back()->with('status', 'Kartu program berhasil ditambahkan.');
    }

    public function updateProgram(Request $request, LandingProgram $program): RedirectResponse
    {
        $program->update($this->validateProgram($request));

        return back()->with('status', 'Kartu program berhasil diperbarui.');
    }

    public function destroyProgram(LandingProgram $program): RedirectResponse
    {
        $program->delete();

        return back()->with('status', 'Kartu program dihapus.');
    }

    public function updateSection(Request $request, string $key): RedirectResponse
    {
        $section = LandingSection::query()->where('section_key', $key)->firstOrFail();
        $section->update($request->validate([
            'kicker' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'body_secondary' => 'nullable|string',
            'cta_primary_text' => 'nullable|string|max:255',
            'cta_primary_link' => 'nullable|string|max:255',
            'cta_secondary_text' => 'nullable|string|max:255',
            'cta_secondary_link' => 'nullable|string|max:255',
            'contact_email' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'extra_text' => 'nullable|string',
        ]));

        return back()->with('status', 'Konten section berhasil disimpan.');
    }

    public function storeItem(Request $request): RedirectResponse
    {
        LandingItem::create($this->validateItem($request));

        return back()->with('status', 'Item konten berhasil ditambahkan.');
    }

    public function updateItem(Request $request, LandingItem $item): RedirectResponse
    {
        $item->update($this->validateItem($request));

        return back()->with('status', 'Item konten berhasil diperbarui.');
    }

    public function destroyItem(LandingItem $item): RedirectResponse
    {
        $item->delete();

        return back()->with('status', 'Item konten dihapus.');
    }

    private function validateSlide(Request $request, ?LandingSlide $slide = null): array
    {
        $request->validate([
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->validate([
            'tag' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cta_text' => 'nullable|string|max:255',
            'cta_link' => 'nullable|string|max:255',
            'slide_style' => 'required|integer|min:1|max:3',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function validateProgram(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:50',
            'color_variant' => 'required|string|max:30',
            'sort_order' => 'required|integer|min:0',
        ]);
    }

    private function validateItem(Request $request): array
    {
        $data = $request->validate([
            'group' => 'required|string|max:50',
            'icon' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'value' => 'nullable|integer|min:0',
            'suffix' => 'nullable|string|max:10',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
