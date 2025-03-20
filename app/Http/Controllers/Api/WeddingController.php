<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wedding;
use Illuminate\Support\Facades\Mail;
use App\Mail\TermNotAvailableMail;
use App\Mail\TermAvailableMail;
use App\Mail\AdminNotificationMail;

class WeddingController extends Controller
{
    public function show($id)
    {
        $wedding = Wedding::findOrFail($id);
        return response()->json($wedding);
    }
    public function checkAvailability(Request $request)
    {
        // Walidacja danych wejściowych
        $validated = $request->validate([
            'weddingDate' => 'required|date',
            'email'       => 'required|email',
            'packages'    => 'required|array',
            'packages.*'  => 'string|in:foto,film,fotoplener'
        ]);

        $date              = $validated['weddingDate'];
        $email             = $validated['email'];
        $requestedPackages = $validated['packages'];

        // Pobierz wszystkie rezerwacje na ten dzień
        $bookingsOnDate = Wedding::whereDate('data', $date)->get();

        // Zliczamy ilość rezerwacji dla każdego pakietu
        $packageUsage = [
            'foto'       => 0,
            'film'       => 0,
            'fotoplener' => 0
        ];

        foreach ($bookingsOnDate as $booking) {
            if (!empty($booking->pakiet)) {
                $bookingPackages = explode('+', $booking->pakiet);
                foreach ($bookingPackages as $package) {
                    $package = trim($package);
                    if (isset($packageUsage[$package])) {
                        $packageUsage[$package]++;
                    }
                }
            }
        }

        // Pakiety, które użytkownik wybrał, ale są niedostępne
        $unavailablePackages = [];
        $availablePackages   = [];
        $allPackages         = ['foto', 'film', 'fotoplener'];
        
        foreach ($requestedPackages as $package) {
            if (isset($packageUsage[$package]) && $packageUsage[$package] >= 2) {
                $unavailablePackages[] = $package;
            } else {
                $availablePackages[] = $package;
            }
        }

        // Znajdź alternatywne pakiety (które nie zostały wybrane, ale są dostępne)
        $alternativePackages = array_filter($allPackages, function ($package) use ($requestedPackages, $packageUsage) {
            return !in_array($package, $requestedPackages) && (!isset($packageUsage[$package]) || $packageUsage[$package] < 2);
        });

        if (!empty($unavailablePackages)) {
            // Termin zajęty dla wybranych pakietów - wysyłamy e-mail z alternatywną propozycją
            Mail::to($email)->send(new TermNotAvailableMail(
                $date,
                $unavailablePackages,
                $availablePackages,
                $alternativePackages
            ));

            return response()->json([
                'available'          => false,
                'message'            => 'Termin jest zajęty dla wybranych pakietów. Wysłaliśmy wiadomość e-mail.',
                'unavailablePackages' => $unavailablePackages,
                'availablePackages'   => array_merge($availablePackages, $alternativePackages)
            ]);
        }

        // Jeśli wszystkie wybrane pakiety są dostępne, wysyłamy standardową ofertę
        Mail::to($email)->send(new TermAvailableMail($date, $requestedPackages));
        Mail::to('programista@salemstudio.pl')->send(new AdminNotificationMail($email, $date, $requestedPackages));


        return response()->json([
            'available' => true,
            'message'   => 'Termin jest wolny. Wysłaliśmy ofertę e-mail.'
        ]);
    }
}
