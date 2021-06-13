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
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\FromView;

class PenjualanExport implements FromCollection, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $penjualan;

    function __construct($penjualan,$sumdiskon,$sumpajak,$sumtotal,$sumtunai,$sumkredit,$sumekop)
    {
        $this->penjualan = $penjualan;
        $this->sumdiskon = $sumdiskon;
        $this->sumpajak = $sumpajak;
        $this->sumtotal = $sumtotal;
        $this->sumtunai = $sumtunai;
        $this->sumkredit = $sumkredit;
        $this->sumekop = $sumekop;
    }

    public function collection()
    {
        return $this->penjualan;
    }


    public function registerEvents(): array
    {
        return [
        
            AfterSheet::class => function (AfterSheet $event) {
                $last_column = Coordinate::stringFromColumnIndex(count($this->penjualan[0]));

                $last_row = count($this->penjualan) + 2 + 1;
                $style_text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ];

                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 2);

                // merge cells for full-width
                // $event->sheet->mergeCells(sprintf('A1:%s1',$last_column));
                $event->sheet->mergeCells('A1:A2');
                $event->sheet->mergeCells('B1:B2');
                $event->sheet->mergeCells('C1:C2');
                $event->sheet->mergeCells('D1:D2');
                $event->sheet->mergeCells('E1:E2');
                $event->sheet->mergeCells('F1:F2');
                $event->sheet->mergeCells('G1:G2');
                $event->sheet->mergeCells('H1:H2');
                $event->sheet->mergeCells('I1:K1');
                // $event->sheet->mergeCells($this->cellsToMergeByColsRow(0,2,3));
                // $event->sheet->mergeCells(sprintf('A2:%s2',$last_column));
                $event->sheet->mergeCells(sprintf('A%d:E%d',$last_row,$last_row));

                // assign cell values
                $event->sheet->setCellValue('A1', 'Penjualan');
                $event->sheet->setCellValue('B1', 'Lokasi');
                $event->sheet->setCellValue('C1', 'Tanggal');
                $event->sheet->setCellValue('D1', 'Nomor');
                $event->sheet->setCellValue('E1', 'Customer');
                $event->sheet->setCellValue('F1', 'Diskon');
                $event->sheet->setCellValue('G1', 'Pajak');
                $event->sheet->setCellValue('H1', 'Total');
                $event->sheet->setCellValue('I1', 'Pembayaran');
                $event->sheet->setCellValue('L1', 'DueDate');
                $event->sheet->setCellValue('I2', 'Ekop');
                $event->sheet->setCellValue('J2', 'Tunai');
                $event->sheet->setCellValue('K2', 'Kredit');
                // $event->sheet->setCellValue('J1','Lokasi');
                // $event->sheet->setCellValue('K1','Lokasi');
                // $event->sheet->setCellValue('L1','Lokasi');
                $event->sheet->setCellValue(sprintf('A%d',$last_row),'Total');
                $event->sheet->setCellValue(sprintf('F%d',$last_row),$this->sumdiskon);
                $event->sheet->setCellValue(sprintf('G%d',$last_row),$this->sumpajak);
                $event->sheet->setCellValue(sprintf('H%d',$last_row),$this->sumtotal);
                $event->sheet->setCellValue(sprintf('I%d',$last_row),$this->sumekop);
                $event->sheet->setCellValue(sprintf('J%d',$last_row),$this->sumtunai);
                $event->sheet->setCellValue(sprintf('K%d',$last_row),$this->sumkredit);


                // assign cell styles
                $event->sheet->getStyle('A:L')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A:L')->getAlignment()->setVertical('center');
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
                $event->sheet->getStyle(sprintf('A%d', $last_row))->applyFromArray($style_text_center);
            },
        ];
    }

    function cellsToMergeByColsRow($start = NULL, $end = NULL, $row = NULL)
    {
        $merge = 'A1:A1';
        if ($start && $end && $row) {
            $start = Coordinate::stringFromColumnIndex($start);
            $end = Coordinate::stringFromColumnIndex($end);
            $merge = "$start{$row}:$end{$row}";
        }

        return $merge;
    }
}
