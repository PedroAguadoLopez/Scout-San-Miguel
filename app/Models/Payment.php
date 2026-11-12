<?php

namespace App\Models;

use App\Enums\FeeType;
use App\Enums\PaymentStatus;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'concept',
    'description',
    'fee_type',
    'period_label',
    'amount_cents',
    'currency',
    'due_date',
    'status',
    'stripe_session_id',
    'stripe_payment_intent_id',
    'paid_at',
])]
class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fee_type' => FeeType::class,
            'amount_cents' => 'integer',
            'due_date' => 'date',
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === PaymentStatus::Pending;
    }

    public function isPaid(): bool
    {
        return $this->status === PaymentStatus::Paid;
    }

    public function formattedAmount(): string
    {
        return number_format($this->amount_cents / 100, 2, ',', '.').' €';
    }

    /**
     * @param  Builder<Payment>  $query
     * @return Builder<Payment>
     */
    #[Scope]
    protected function pending(Builder $query): Builder
    {
        return $query->where('status', PaymentStatus::Pending);
    }

    /**
     * @param  Builder<Payment>  $query
     * @return Builder<Payment>
     */
    #[Scope]
    protected function paid(Builder $query): Builder
    {
        return $query->where('status', PaymentStatus::Paid);
    }
}
