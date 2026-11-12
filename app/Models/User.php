<?php

namespace App\Models;

use App\Enums\ScoutSection;
use App\Enums\UserRole;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'birth_date', 'role', 'scout_section', 'phone', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'role' => UserRole::class,
            'scout_section' => ScoutSection::class,
            'password' => 'hashed',
        ];
    }

    /**
     * @return HasMany<Payment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isAdult(): bool
    {
        return $this->role?->canPay() ?? false;
    }

    public function isMinor(): bool
    {
        return ! $this->isAdult();
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public static function resolveRoleFromBirthDate(CarbonInterface $birthDate): UserRole
    {
        return $birthDate->age >= 18 ? UserRole::Adult : UserRole::Minor;
    }
}
