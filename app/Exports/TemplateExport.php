<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TemplateExport implements FromCollection, WithHeadings, WithTitle
{
    protected $headings;
    protected $title;

    public function __construct(array $headings, string $title = 'Template')
    {
        $this->headings = $headings;
        $this->title = $title;
    }

    public function collection()
    {
        return collect([]);
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function title(): string
    {
        return $this->title;
    }
}
