<?php

namespace App\Http\Controllers;

use App\Models\Camp;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampController extends Controller
{
    public function index(Request $request): View
    {
        $selectedYear = $request->filled('year') ? $request->integer('year') : null;

        $years = Camp::query()
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $camps = Camp::query()
            ->with('photos')
            ->forYear($selectedYear)
            ->orderByDesc('year')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        return view('camps.index', [
            'camps' => $camps,
            'years' => $years,
            'selectedYear' => $selectedYear,
        ]);
    }

    public function show(Camp $camp): View
    {
        $camp->load('photos');

        return view('camps.show', [
            'camp' => $camp,
        ]);
    }
}
