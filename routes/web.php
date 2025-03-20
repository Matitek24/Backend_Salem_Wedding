<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormSubmissionExportController;
use App\Exports\WeddingsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\UmowaController;
use App\Http\Controllers\Api\WeddingController;


Route::get('/admin/form-submissions-export', [FormSubmissionExportController::class, 'export'])
    ->name('form_submissions.export');

    Route::get('/admin/weddings-export', function () {
        return Excel::download(new WeddingsExport, 'weddings.xlsx');
    })->name('weddings.export');

Route::get('/', function () {
    return view('welcome');
});


Route::get('/umowa/{id}/pdf', [UmowaController::class, 'generatePdf'])->name('umowa.pdf');

// TYLKO DO TWORZENIA LINK SIGNED !!!!!!
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
Route::get('/umowa/{wedding_id}', function () {
    return view('app');
})->name('umowa.show')->middleware('signed');
