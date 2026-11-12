<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $pendingPaymentsCount = $user->isAdult()
            ? $user->payments()->pending()->count()
            : 0;

        return view('profile', [
            'user' => $user,
            'pendingPaymentsCount' => $pendingPaymentsCount,
        ]);
    }
}
