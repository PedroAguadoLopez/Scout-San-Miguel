<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdult();
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->isAdult() && $payment->user_id === $user->id;
    }

    public function pay(User $user, Payment $payment): bool
    {
        return $user->isAdult()
            && $payment->user_id === $user->id
            && $payment->isPending();
    }
}
