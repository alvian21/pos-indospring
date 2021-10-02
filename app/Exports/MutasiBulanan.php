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

class MutasiBulanan implements FromCollection, WithEvents, WithMapping
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
            $data['KodeBarang'],
            $data['Nama'],
            $data['SaldoAwal'],
            $data['Pembelian'],
            $data['IN'],
            $data['Retur'],
            $data['OUT'],
            $data['Rusak'],
            $data['Penjualan'],
            $data['Opname'],
            $data['Saldo'],
            $data['Akhir'],
            $data['HPP'],
            $data['HargaJual'],
            $data['Laba'],
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

                $event->sheet->mergeCells('A1:O1');
                $event->sheet->mergeCells('A2:O2');

                $event->sheet->mergeCells(sprintf('A%d:B%d', $last_row, $last_row));

                // assign cell values
                $event->sheet->setCellValue('A1', 'Laporan Mutasi Bulanan');
                $event->sheet->setCellValue('A2', 'Periode ' . $this->periode);
                $event->sheet->setCellValue('A3', 'Kode Barang');
                $event->sheet->setCellValue('B3', 'Nama Barang');
                $event->sheet->setCellValue('C3', 'Saldo Awal');
                $event->sheet->setCellValue('D3', 'Pembelian');
                $event->sheet->setCellValue('E3', 'Transfer IN');
                $event->sheet->setCellValue('F3', 'Retur Pembelian');
                $event->sheet->setCellValue('G3', 'Transfer OUT');
                $event->sheet->setCellValue('H3', 'Hilang / Rusak');
                $event->sheet->setCellValue('I3', 'Penjualan');
                $event->sheet->setCellValue('J3', 'Opname');
                $event->sheet->setCellValue('K3', 'Saldo');
                $event->sheet->setCellValue('L3', 'Saldo Akhir');
                $event->sheet->setCellValue('M3', 'HPP');
                $event->sheet->setCellValue('N3', 'Harga Jual');
                $event->sheet->setCellValue('O3', 'Laba');


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
                $event->sheet->getColumnDimension('N')->setAutoSize(true);
                $event->sheet->getColumnDimension('O')->setAutoSize(true);

                $event->sheet->getStyle(sprintf('A%d', $last_row))->applyFromArray($style_text_center);
            },
        ];
    }
}
