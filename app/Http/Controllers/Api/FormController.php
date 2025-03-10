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
            'firstName'         => 'required|string|max:255',
            'lastName'          => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'weddingDate'       => 'required|date',
            'weddingLocation'   => 'required|string|max:255',
            'marriageLocation'  => 'required|string|max:255',
        ]);

        // Mapowanie z camelCase na snake_case przy zapisie do bazy
        $submission = FormSubmission::create([
            'first_name'        => $validated['firstName'],
            'last_name'         => $validated['lastName'],
            'email'             => $validated['email'],
            'wedding_date'      => $validated['weddingDate'],
            'wedding_location'  => $validated['weddingLocation'],
            'marriage_location' => $validated['marriageLocation'],
        ]);

        return response()->json([
            'message' => 'Dane zostały zapisane pomyślnie!',
            'data'    => $submission
        ], 201);
    }
}
