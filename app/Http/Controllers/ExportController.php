<?php

namespace App\Http\Controllers;

use App\Exports\AuthorsExport;
use App\Exports\BooksExport;
use App\Exports\LibraryExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{


    public function exportLibrary()
    {
        $date = date('Y-m-d');
        return Excel::download(new LibraryExport, "reporte_biblioteca_{$date}.xlsx");
    }
}
