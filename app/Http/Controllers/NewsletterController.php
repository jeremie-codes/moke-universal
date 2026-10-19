<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:newsletters,email'],
        ]);

        \App\Models\newsletter::create([
            'email' => strtolower($validated['email']),
        ]);

        return redirect()
            ->route('home')
            ->with('newsletter_success', 'Votre inscription à la newsletter a bien été enregistrée.');
    }
}
