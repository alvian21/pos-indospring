<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\FromView;

class MinimumStokExport implements FromCollection, WithEvents, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $minimumstok;

    function __construct($minimumstok)
    {
        $this->minimumstok = $minimumstok;
    }

    public function collection()
    {
        return $this->minimumstok;
    }

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {

                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 1);
                // assign cell values
                $event->sheet->setCellValue('A1', 'No');
                $event->sheet->setCellValue('B1', 'Kategori');
                $event->sheet->setCellValue('C1', 'No');
                $event->sheet->setCellValue('D1', 'Kode');
                $event->sheet->setCellValue('E1', 'Nama');
                $event->sheet->setCellValue('F1', 'Minimum Stok');
                $event->sheet->setCellValue('G1', 'Stok Sekarang');
                // assign cell styles
                $event->sheet->getStyle('A:G')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A:G')->getAlignment()->setVertical('center');
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
            },
        ];
    }

    public function map($minimumstok): array
    {
        return [
            $minimumstok['No'],
            $minimumstok['Kategori'],
            $minimumstok['NomorGroup'],
            $minimumstok['Kode'],
            $minimumstok['Nama'],
            $minimumstok['MinimumStok'],
            $minimumstok['Saldo'],

        ];
    }
}
