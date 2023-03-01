<?php

namespace App\Imports;

use App\Models\Vesta;
use Maatwebsite\Excel\Concerns\ToModel;

class FiveSheetImport implements ToModel
{

    public function model(array $row)
    {
        if($row[0] != 'Pipe' && $row[0] != '' && $row[0] != null){
            return new Vesta([
                'parent_id' => 5,
                'id_pok' => $row[0],
                'identificator' => $row[1],
                'name_obj' => $row[2],
                'Ro' => $row[5],
                'Qin' => $row[6],
                'q' => $row[7],
                'Pin' => $row[8],
                'Pout' => $row[9],
                'Tin' => $row[10],
                'Tout' => $row[11],
                'Qz' => $row[12],
                'Vin' => $row[13],
                'Vout' => $row[14],
            ]);
        }
    }
}
