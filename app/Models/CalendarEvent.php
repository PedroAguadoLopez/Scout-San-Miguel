<?php

namespace App\Models;

use App\Enums\ScoutSection;
use Carbon\CarbonInterface;
use Database\Factories\CalendarEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'location', 'scout_section', 'event_type', 'starts_at', 'ends_at', 'all_day'])]
class CalendarEvent extends Model
{
    /** @use HasFactory<CalendarEventFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scout_section' => ScoutSection::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'all_day' => 'boolean',
        ];
    }

    /**
     * @param  Builder<CalendarEvent>  $query
     * @return Builder<CalendarEvent>
     */
    #[Scope]
    protected function inRange(Builder $query, CarbonInterface $start, CarbonInterface $end): Builder
    {
        return $query
            ->where('starts_at', '<=', $end)
            ->where(function (Builder $subQuery) use ($start): void {
                $subQuery
                    ->where('ends_at', '>=', $start)
                    ->orWhere(function (Builder $noEnd) use ($start): void {
                        $noEnd->whereNull('ends_at')->where('starts_at', '>=', $start);
                    });
            });
    }

    /**
     * @param  Builder<CalendarEvent>  $query
     * @return Builder<CalendarEvent>
     */
    #[Scope]
    protected function forSection(Builder $query, ?ScoutSection $section): Builder
    {
        if ($section === null || $section === ScoutSection::Grupo) {
            return $query;
        }

        return $query->whereIn('scout_section', [ScoutSection::Grupo->value, $section->value]);
    }
}
