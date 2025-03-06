<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormSubmissionExportController;
use App\Exports\WeddingsExport;
use Maatwebsite\Excel\Facades\Excel;


Route::get('/admin/form-submissions-export', [FormSubmissionExportController::class, 'export'])
    ->name('form_submissions.export');

    Route::get('/admin/weddings-export', function () {
        return Excel::download(new WeddingsExport, 'weddings.xlsx');
    })->name('weddings.export');

Route::get('/', function () {
    return view('welcome');
});
