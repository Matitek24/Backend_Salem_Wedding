<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wedding;
use Illuminate\Support\Facades\Mail;
use App\Mail\TermNotAvailableMail;
use App\Mail\TermAvailableMail;

class WeddingController extends Controller
{
    public function checkAvailability(Request $request)
    {
        // Walidacja danych – email jest wymagany
        $validated = $request->validate([
            'weddingDate' => 'required|date',
            'email'       => 'required|email'
        ]);

        $date = $validated['weddingDate'];
        $email = $validated['email'];

        // Liczba rezerwacji na ten dzień
        $bookingsCount = Wedding::whereDate('data', $date)->count();

        if ($bookingsCount >= 2) {
            // Termin zajęty – wysyłamy maila informującego
            Mail::to($email)->send(new TermNotAvailableMail($date));
            return response()->json([
                'available' => false,
                'message'   => 'Termin jest zajęty. Wysłaliśmy wiadomość e-mail.'
            ]);
        } else {
            // Termin wolny – wysyłamy ofertę mailową
            Mail::to($email)->send(new TermAvailableMail($date));
            return response()->json([
                'available' => true,
                'message'   => 'Termin jest wolny. Wysłaliśmy ofertę e-mail.'
            ]);
        }
    }
}
