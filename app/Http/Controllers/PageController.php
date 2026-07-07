<?php

namespace App\Http\Controllers;

use App\Models\LandingItem;
use App\Models\LandingProgram;
use App\Models\LandingSection;
use App\Models\LandingSlide;
use App\Models\Program;
use Illuminate\View\View;

class PageController extends Controller
{
    public function landing(): View
    {
        $sections = LandingSection::map();

        return view('pages.landing', [
            'slides' => LandingSlide::where('is_active', true)->orderBy('sort_order')->get(),
            'landingPrograms' => LandingProgram::orderBy('sort_order')->get(),
            'programCount' => Program::count(),
            'sections' => $sections,
            'heroPoints' => LandingItem::group('hero_point')->get(),
            'quickLinks' => LandingItem::group('quick_link')->get(),
            'stats' => LandingItem::group('stat')->get(),
            'aboutValues' => LandingItem::group('about_value')->get(),
            'features' => LandingItem::group('feature')->get(),
            'ortuPoints' => LandingItem::group('ortu_point')->get(),
            'kemitraanPoints' => LandingItem::group('kemitraan_point')->get(),
            'steps' => LandingItem::group('step')->get(),
        ]);
    }

    public function login(): View
    {
        return view('pages.login');
    }
}
