<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LibraryExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new AuthorsExport();
        $sheets[] = new BooksExport();

        return $sheets;
    }
}
