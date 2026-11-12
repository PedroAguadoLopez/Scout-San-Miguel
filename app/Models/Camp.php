<?php

namespace App\Models;

use Database\Factories\CampFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'location', 'year', 'start_date', 'end_date', 'summary', 'description', 'cover_image', 'is_featured'])]
class Camp extends Model
{
    /** @use HasFactory<CampFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * @return HasMany<CampPhoto, $this>
     */
    public function photos(): HasMany
    {
        return $this->hasMany(CampPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @param  Builder<Camp>  $query
     * @return Builder<Camp>
     */
    #[Scope]
    protected function featured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * @param  Builder<Camp>  $query
     * @return Builder<Camp>
     */
    #[Scope]
    protected function forYear(Builder $query, ?int $year): Builder
    {
        if ($year === null) {
            return $query;
        }

        return $query->where('year', $year);
    }
}
