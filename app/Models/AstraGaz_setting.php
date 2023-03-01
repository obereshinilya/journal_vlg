<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstraGaz_setting extends Model{
    protected $table='public.astragaz_setting';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'p_lu1', 'q_lu1', 't_lu1',  'p_lu2', 'q_lu2', 't_lu2',
        'q_sn', 'p_in', 'p_vsas',
        'p_nagn', 'p_out', 't_vsas',
        't_nagn', 't_avo', 't_vozd'
    ];


}

?>
