<?php

namespace App\Http\Controllers;

use App\Enums\ScoutSection;
use App\Models\CalendarEvent;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __invoke(Request $request): View
    {
        $now = CarbonImmutable::now();
        $month = max(1, min(12, $request->integer('month', $now->month)));
        $year = max(2020, min(2035, $request->integer('year', $now->year)));
        $selectedSection = ScoutSection::tryFrom($request->string('section')->toString());

        $currentMonth = CarbonImmutable::create($year, $month, 1);
        $gridStart = $currentMonth->startOfWeek(CarbonImmutable::MONDAY);
        $gridEnd = $currentMonth->endOfMonth()->endOfWeek(CarbonImmutable::SUNDAY);

        $monthEvents = CalendarEvent::query()
            ->inRange($gridStart->startOfDay(), $gridEnd->endOfDay())
            ->forSection($selectedSection)
            ->orderBy('starts_at')
            ->orderBy('id')
            ->get();

        $eventsByDay = [];

        foreach ($monthEvents as $event) {
            $cursor = $event->starts_at->startOfDay();
            $lastDay = ($event->ends_at ?? $event->starts_at)->startOfDay();

            if ($cursor->lt($gridStart)) {
                $cursor = $gridStart->startOfDay();
            }

            if ($lastDay->gt($gridEnd)) {
                $lastDay = $gridEnd->startOfDay();
            }

            while ($cursor->lte($lastDay)) {
                $eventsByDay[$cursor->toDateString()][] = $event;
                $cursor = $cursor->addDay();
            }
        }

        $weeks = [];
        $dayCursor = $gridStart;

        while ($dayCursor->lte($gridEnd)) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $dateKey = $dayCursor->toDateString();
                $week[] = [
                    'date' => $dayCursor,
                    'inMonth' => $dayCursor->month === $currentMonth->month,
                    'isToday' => $dayCursor->isSameDay($now),
                    'events' => $eventsByDay[$dateKey] ?? [],
                ];
                $dayCursor = $dayCursor->addDay();
            }

            $weeks[] = $week;
        }

        $yearEvents = CalendarEvent::query()
            ->whereYear('starts_at', $year)
            ->forSection($selectedSection)
            ->orderBy('starts_at')
            ->orderBy('id')
            ->get();

        $previousMonth = $currentMonth->subMonth();
        $nextMonth = $currentMonth->addMonth();

        return view('calendar.index', [
            'currentMonth' => $currentMonth,
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonth,
            'weeks' => $weeks,
            'yearEvents' => $yearEvents,
            'sections' => ScoutSection::cases(),
            'selectedSection' => $selectedSection,
            'year' => $year,
        ]);
    }
}
