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

class SimpanPinjam implements FromCollection, WithEvents, WithMapping
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
            $data['TanggalPengajuan'],
            $data['Nama'],
            $data['SubDept'],
            $data['Pinjaman'],
            $data['BerapaKaliBayar'].' X',
            $data['CicilanTotal'],
            $data['CicilanBunga'],
            $data['CicilanBunga'] *  $data['BerapaKaliBayar'],
            $lastdate
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

                $event->sheet->mergeCells('A1:I1');
                $event->sheet->mergeCells('A2:I2');

                $event->sheet->mergeCells(sprintf('A%d:B%d', $last_row, $last_row));

                // assign cell values
                $event->sheet->setCellValue('A1', 'Laporan Simpan Pinjam');
                $event->sheet->setCellValue('A2', 'Periode ' . $this->periode);
                $event->sheet->setCellValue('A3', 'Tanggal');
                $event->sheet->setCellValue('B3', 'Nama Anggota');
                $event->sheet->setCellValue('C3', 'Sub Dept');
                $event->sheet->setCellValue('D3', 'Jumlah');
                $event->sheet->setCellValue('E3', 'Angsuran');
                $event->sheet->setCellValue('F3', 'Angsuran per bln');
                $event->sheet->setCellValue('G3', 'Jasa per bln');
                $event->sheet->setCellValue('H3', 'Total Pendapatan');
                $event->sheet->setCellValue('I3', 'POT');


                // assign cell styles
                $event->sheet->getStyle('A:I')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A:I')->getAlignment()->setVertical('center');
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);

                $event->sheet->getStyle(sprintf('A%d', $last_row))->applyFromArray($style_text_center);
            },
        ];
    }
}
