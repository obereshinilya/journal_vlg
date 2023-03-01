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

class SutExport implements FromView, WithStyles, ShouldAutoSize
{

    private $title, $data, $num_days, $month;

    public function __construct($title, $data, $num_days, $month)
    {
        $this->title = $title;
        $this->data = $data;
        $this->num_days = $num_days;
        $this->month = $month;
    }

    public function view(): View
    {
        return view('web.excel.excel_sut_params', [
            'data' => $this->data,
            'title' => $this->title,
            'num_days' => $this->num_days,
            'month' => $this->month
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
        $styleArray2 = [
            'font' => [
                'size'=>20
            ]
        ];

        $sheet->getStyle('A1')->applyFromArray($styleArray2);
        $sheet->getStyle('A1:AG2')->applyFromArray($styleArray);
        $sheet->getRowDimension('1')->setRowHeight(50);
    }

}
