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


class LabaRugi implements FromCollection, WithEvents, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;

    function __construct($data, $periode, $lastdate)
    {
        $this->data = $data;
        $this->periode = $periode;
        $this->lastdate = $lastdate;
    }


    public function collection()
    {
        return $this->data;
    }

    public function map($data): array
    {
        $lastdate = $this->lastdate;
        return [
            '',
            $data['Januari'],
            $data['Februari'],
            $data['Maret'],
            $data['April'],
            $data['Mei'],
            $data['Juni'],
            $data['Juli'],
            $data['Agustus'],
            $data['September'],
            $data['Oktober'],
            $data['November'],
            $data['Desember'],

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
                $event->sheet->insertNewRowBefore(1, 3);

                $event->sheet->mergeCells('A1:M1');
                $event->sheet->mergeCells('A2:M2');

                // $event->sheet->mergeCells(sprintf('A%d:B%d', $last_row, $last_row));

                // assign cell values
                $event->sheet->setCellValue('A1', 'Laporan Laba Rugi');
                $event->sheet->setCellValue('A2', 'Periode ' . $this->periode);
                $event->sheet->setCellValue('A3', 'Keterangan');
                $event->sheet->setCellValue('A4', 'Penjualan');
                $event->sheet->setCellValue('A5', 'Potongan Penjualan');
                $event->sheet->setCellValue('A6', 'Bunga');
                $event->sheet->setCellValue('A7', 'Total');
                $event->sheet->setCellValue('A8', 'Persediaan Awal');
                $event->sheet->setCellValue('A9', 'Pembelian');
                $event->sheet->setCellValue('A10', 'Retur Barang (-)');
                $event->sheet->setCellValue('A11', 'Persediaan Akhir (-)');
                $event->sheet->setCellValue('A12', 'Harga Pokok Penjualan');
                $event->sheet->setCellValue('A13', 'LABA KOTOR');

                $event->sheet->setCellValue('A3', 'Keterangan');
                $event->sheet->setCellValue('B3', 'Januari');
                $event->sheet->setCellValue('C3', 'Februari');
                $event->sheet->setCellValue('D3', 'Maret');
                $event->sheet->setCellValue('E3', 'April');
                $event->sheet->setCellValue('F3', 'Mei');
                $event->sheet->setCellValue('G3', 'Juni');
                $event->sheet->setCellValue('H3', 'Juli');
                $event->sheet->setCellValue('I3', 'Agustus');
                $event->sheet->setCellValue('J3', 'September');
                $event->sheet->setCellValue('K3', 'Oktober');
                $event->sheet->setCellValue('L3', 'November');
                $event->sheet->setCellValue('M3', 'Desember');

                // assign cell styles
                $event->sheet->getStyle('A:M')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A:M')->getAlignment()->setVertical('center');
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getColumnDimension('J')->setAutoSize(true);
                $event->sheet->getColumnDimension('K')->setAutoSize(true);
                $event->sheet->getColumnDimension('L')->setAutoSize(true);
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getStyle(sprintf('A%d', $last_row))->applyFromArray($style_text_center);
            },
        ];
    }
}
