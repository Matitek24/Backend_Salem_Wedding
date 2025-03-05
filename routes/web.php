<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormSubmissionExportController;

Route::get('/admin/form-submissions-export', [FormSubmissionExportController::class, 'export'])
    ->name('form_submissions.export');


Route::get('/', function () {
    return view('welcome');
});
