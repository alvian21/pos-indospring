<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\FromView;

class TagihanKredit implements FromCollection, WithEvents, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $data;

    function __construct($data, $periode, $total)
    {
        $this->data = $data;
        $this->periode = $periode;
        $this->total = $total;
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($data): array
    {
        return [
            $data['SubDept'],
            $data['Kode'],
            $data['Nama'],
            $data['SaldoBelanjaKredit']
        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {
                $last_column = Coordinate::stringFromColumnIndex(count($this->data[0]));

                $last_row = count($this->data) + 2 + 1;
                $style_text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ];

                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 2);

                $event->sheet->mergeCells('A1:D1');
                $event->sheet->mergeCells('A2:D2');

                $event->sheet->mergeCells(sprintf('A%d:B%d', $last_row, $last_row));

                // assign cell values
                $event->sheet->setCellValue('A1', 'Laporan Penjualan Kredit');
                $event->sheet->setCellValue('A2', 'Periode ' . $this->periode);
                $event->sheet->setCellValue('A3', 'SubDept');
                $event->sheet->setCellValue('B3', 'Kode');
                $event->sheet->setCellValue('C3', 'Nama');
                $event->sheet->setCellValue('D3', 'Saldo Belanja Kredit');

                $event->sheet->setCellValue(sprintf('A%d', $last_row), 'Total');
                $event->sheet->setCellValue(sprintf('C%d', $last_row), $this->total);


                // assign cell styles
                $event->sheet->getStyle('A:D')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A:D')->getAlignment()->setVertical('center');
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);

                $event->sheet->getStyle(sprintf('A%d', $last_row))->applyFromArray($style_text_center);
            },
        ];
    }
}
