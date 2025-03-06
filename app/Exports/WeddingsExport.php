<?php

namespace App\Exports;

use App\Models\Wedding;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class WeddingsExport implements FromCollection, WithHeadings
{
    use Exportable;

    /**
     * Zwraca dane do eksportu
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Wedding::all([
            'imie1', 'imie2', 'data', 'typ_wesela', 'sala', 'koscol', 'liczba_gosci'
        ]);
    }

    /**
     * Nagłówki kolumn w pliku Excel
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Panna Młoda',
            'Pan Młody',
            'Data Wesela',
            'Typ Wesela',
            'Sala Weselna',
            'Kościół',
            'Liczba Gości',
        ];
    }
}
