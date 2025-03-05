<?php

namespace App\Http\Controllers;

use App\Exports\FormSubmissionsExport;
use Maatwebsite\Excel\Facades\Excel;

class FormSubmissionExportController extends Controller
{
    public function export()
    {
        return Excel::download(new FormSubmissionsExport, 'form_submissions.xlsx');
    }
}
