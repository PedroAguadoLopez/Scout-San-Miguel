<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('contact', [
            'scout' => config('scout'),
        ]);
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        ContactMessage::create($request->validated());

        return redirect()
            ->route('contact.create')
            ->with('status', '¡Gracias por escribirnos! Hemos recibido tu mensaje y el Kraal del Grupo Scout San Miguel se pondrá en contacto contigo.');
    }
}
