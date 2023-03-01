<?php

namespace App\Imports;

use App\Models\Vesta;
use Maatwebsite\Excel\Concerns\ToModel;

class FourSheetImport implements ToModel
{

    public function model(array $row)
    {
        if($row[0] != 'In' && $row[0] != '' && $row[0] != null){
            return new Vesta([
                'parent_id' => 4,
                'id_pok' => $row[0],
                'identificator' => $row[1],
                'name_obj' => $row[2],
                'p' => $row[5],
                'q' => $row[6],
                't' => $row[7],
                'Ro' => $row[8],
                'co2' => $row[9],
                'n2' => $row[10],
                'Hsn' => $row[11],
            ]);
        }
    }
}
