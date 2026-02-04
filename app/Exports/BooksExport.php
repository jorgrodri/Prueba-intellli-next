<?php

namespace App\Exports;

use App\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Book::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'Publish Date',
            'Author ID',
            'Created At',
            'Updated At',
        ];
    }
    public function title(): string
    {
        return 'Libros';
    }
}
