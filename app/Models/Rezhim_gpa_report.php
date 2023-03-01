<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rezhim_gpa_report extends Model{
    protected $table='public.report_rezhim_gpa';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'date', 'time', 'time_rezhim',
        'rezhim', 'tvd', 'priv_tvd',
        'tnd', 'Pin', 'Pout',
        'Tvdv', 'Pvdv', 'Tin',
        'Tout', 'Qtg', 'St_sj',
        'Qcbn', 'Tavo', 'Tvozd',
        'q', 'Pkol', 'Tpodsh',
        'Tgg', 'Zapas', 'Pbuf', 'number_gpa', 'mokveld_status', 'mokveld_zadanie'
    ];


}

?>
