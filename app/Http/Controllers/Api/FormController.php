<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormSubmission;

class FormController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName'   => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'weddingDate' => 'required|date',
            'services'    => 'required|string',
            'miejscowosc'  => 'required|string|max:255',
        ]);

        // Mapujemy dane do struktury zgodnej z migracją: kolumna 'pakiet' przechowuje zakres usług
        $submission = FormSubmission::create([
            'first_name'   => $validated['firstName'],
            'email'        => $validated['email'],
            'wedding_date' => $validated['weddingDate'],
            'pakiet'       => $validated['services'],
            'miejscowosc'  => $validated['miejscowosc'],
        ]);

        return response()->json([
            'message' => 'Dane zostały zapisane pomyślnie!',
            'data'    => $submission
        ], 201);
    }
}
