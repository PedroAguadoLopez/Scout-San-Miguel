<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Camp;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredCamps = Camp::query()
            ->featured()
            ->withCount('photos')
            ->orderByDesc('year')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $upcomingEventsCount = CalendarEvent::query()
            ->where('starts_at', '>=', now()->startOfDay())
            ->count();

        return view('home', [
            'featuredCamps' => $featuredCamps,
            'upcomingEventsCount' => $upcomingEventsCount,
            'scout' => config('scout'),
        ]);
    }
}
