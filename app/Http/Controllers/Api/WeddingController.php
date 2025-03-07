<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wedding;

class WeddingController extends Controller
{
    public function checkAvailability(Request $request)
    {
        // Walidacja daty
        $validated = $request->validate([
            'weddingDate' => 'required|date',
        ]);

        $date = $validated['weddingDate'];

        // Liczymy, ile rezerwacji istnieje dla wybranej daty
        $bookingsCount = Wedding::whereDate('data', $date)->count();

        // Sprawdzamy, czy przekroczono limit rezerwacji (2 na dany dzień)
        if ($bookingsCount >= 2) {
            return response()->json([
                'available' => false,
                'message'   => 'Termin jest zajęty.'
            ]);
        } else {
            return response()->json([
                'available' => true,
                'message'   => 'Termin jest wolny.'
            ]);
        }
    }
}
