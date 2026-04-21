<?php
require 'vendor/autoload.php';
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class HeaderImport implements \Maatwebsite\Excel\Concerns\ToCollection, \Maatwebsite\Excel\Concerns\WithHeadingRow
{
    public function collection(\Illuminate\Support\Collection $rows)
    {
        if ($rows->isNotEmpty()) {
            echo "Headers: " . implode(', ', array_keys($rows->first()->toArray())) . "\n";
        } else {
            echo "No data found.\n";
        }
    }
}

// Direct usage without Laravel app if needed, but it's easier to run via artisan tinker or a script
