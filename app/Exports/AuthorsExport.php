<?php

namespace App\Exports;

use App\Author;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AuthorsExport implements FromCollection, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Author::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Last Name',
            'Books Count',
            'Created At',
            'Updated At',
        ];
    }
    public function title(): string
    {
        return 'Autores';
    }
}
