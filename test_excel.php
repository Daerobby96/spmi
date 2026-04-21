<?php
require 'vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('KUESIONER ALUMNI POLITEKNIK KRAKATAU - Template PDDIKTI.xlsx');
$sheet = $spreadsheet->getActiveSheet();
$headers = [];
foreach ($sheet->getRowIterator(1, 1) as $row) {
    foreach ($row->getCellIterator() as $cell) {
        $headers[] = $cell->getValue();
    }
}
file_put_contents('headers.json', json_encode($headers));
