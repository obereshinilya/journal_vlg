<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OperSKV_Export implements FromView, WithStyles, ShouldAutoSize
{

    private $title, $uphg, $timestamp, $data;

    public function __construct($title, $uphg, $timestamp, $data)
    {
        $this->title = $title;
        $this->uphg = $uphg;
        $this->timestamp = $timestamp;
        $this->data = $data;
    }

    public function view(): View
    {
        return view('web.excel.excel_oper_skv', [
            'title' => $this->title,
            'uphg' => $this->uphg,
            'timestamp' => $this->timestamp,
            'data' => $this->data
        ]);
    }

    public function styles(Worksheet $sheet)
    {

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],

        ];
        $styleArray3 = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
//
//            'alignment' => [
//                'horizontal' => Alignment::HORIZONTAL_CENTER,
//                'vertical' => Alignment::VERTICAL_CENTER,
//                'wrapText' => true,
//            ],

        ];

        $sheet->getStyle('A3:I40')->applyFromArray($styleArray3);
        $sheet->getStyle('A1:I2')->applyFromArray($styleArray);
//        $sheet->getRowDimension('1')->setRowHeight(50);
    }

}
