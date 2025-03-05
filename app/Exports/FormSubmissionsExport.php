<?php

namespace App\Exports;

use App\Models\FormSubmission;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class FormSubmissionsExport implements FromQuery, WithHeadings
{
    use Exportable;

    /**
     * Pobiera dane do eksportu.
     */
    public function query()
    {
        return FormSubmission::query();
    }

    /**
     * Definiuje nagłówki kolumn w pliku Excel.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Imię',
            'Nazwisko',
            'Utworzono',
            'Zaktualizowano',
        ];
    }
}
