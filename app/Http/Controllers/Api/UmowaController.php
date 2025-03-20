<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Umowa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UmowaController extends Controller
{
    /**
     * Zapisuje nową umowę do bazy.
     */
    public function store(Request $request)
    {
        try {
            // Walidacja danych – upewnij się, że wedding_id istnieje w tabeli weddings
            $validatedData = $request->validate([
                'wedding_id'      => 'required|exists:weddings,id',
                'sala'            => 'required|string',
                'koscol'          => 'required|string',
                'imie'            => 'required|string',
                'nazwisko'        => 'required|string',
                'pesel'           => 'nullable|string|max:11',
                'data_podpisania' => 'nullable|date',
                'status'          => 'nullable|in:utworzona,podpisana,anulowana',
                'adres'           => 'required|string',
                'nip'             => 'nullable|string|max:10',
                'telefon_mlodego' => 'required|string',
                'telefon_mlodej'  => 'required|string',
            ]);

            // Jeśli status nie został podany, ustawiamy domyślną wartość
            if (!isset($validatedData['status'])) {
                $validatedData['status'] = 'utworzona';
            }

            // Tworzenie rekordu umowy
            $umowa = Umowa::create($validatedData);

            return response()->json([
                'message' => 'Umowa została zapisana pomyślnie.',
                'umowa'   => $umowa
            ], 201);
        } catch (\Exception $e) {
            Log::error('Błąd podczas zapisywania umowy: ' . $e->getMessage());

            return response()->json([
                'message' => 'Wystąpił błąd podczas zapisywania umowy.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
