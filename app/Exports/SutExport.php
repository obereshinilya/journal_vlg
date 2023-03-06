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

    private $title, $data, $num_days, $month, $area;

    public function __construct($title, $data, $num_days, $month, $area)
    {
        $this->title = $title;
        $this->data = $data;
        $this->num_days = $num_days;
        $this->month = $month;
        $this->area = $area;
    }

    public function view(): View
    {
        if (!$this->area) {
            return view('web.excel.excel_sut_params', [
                'data' => $this->data,
                'title' => $this->title,
                'num_days' => $this->num_days,
                'month' => $this->month
            ]);
        } else {
            return view('web.excel.excel_sut_param_area', [
                'data' => $this->data,
                'title' => $this->title,
                'num_days' => $this->num_days,
                'month' => $this->month
            ]);
        }
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
                'size' => 20
            ]
        ];
        $alphabet = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'aa', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az',

        ];
        $diap = 'A1:' . mb_strtoupper($alphabet[$this->num_days + 1]) . '2';
        $sheet->getStyle('A1')->applyFromArray($styleArray2);
        $sheet->getStyle($diap)->applyFromArray($styleArray);
        $sheet->getRowDimension('1')->setRowHeight(50);
    }

}
