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

class HourExport implements FromView, WithStyles, ShouldAutoSize
{

    private $title, $data, $count, $area;

    public function __construct($title, $data, $count, $area)
    {
        $this->title = $title;
        $this->data = $data;
        $this->count = $count;
        $this->area = $area;
    }

    public function view(): View
    {
        if (!$this->area) {
            return view('web.excel.excel_hour_params', [
                'data' => $this->data,
                'title' => $this->title
            ]);
        } else {
            return view('web.excel.excel_hour_params_area', [
                'data' => $this->data,
                'title' => $this->title
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
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
        ];
        $diap = 'A1:' . mb_strtoupper($alphabet[$this->count + 1]) . '2';
        $sheet->getStyle('A1')->applyFromArray($styleArray2);
        $sheet->getStyle($diap)->applyFromArray($styleArray);
        $sheet->getRowDimension('1')->setRowHeight(50);
    }

}
