<?php

namespace App\Imports;

use App\Models\Vesta;
use Maatwebsite\Excel\Concerns\ToModel;

class SecondSheetImport implements ToModel
{

    public function model(array $row)
    {
        if($row[0] != 'Valve' && $row[0] != '' && $row[0] != null){
            return new Vesta([
                'parent_id' => 2,
                'id_pok' => $row[0],
                'identificator' => $row[1],
                'name_obj' => $row[2],
                'Pin' => $row[5],
                'Qin' => $row[6],
                'Tin' => $row[7],
                'Pout' => $row[8],
                'Tout' => $row[9],
            ]);
        }
    }
}
