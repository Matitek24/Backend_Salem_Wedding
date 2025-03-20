<?php

namespace App\Http\Controllers;

use App\Models\Umowa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class UmowaController extends Controller
{
    public function generatePdf($id)
    {
        $umowa = Umowa::findOrFail($id);

        $pdf = Pdf::loadView('umowy.pdf', compact('umowa'));

        return $pdf->download("umowa-{$umowa->pesel}|{$umowa->data_podpisania}.pdf");
    }
}
