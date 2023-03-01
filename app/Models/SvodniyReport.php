<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SvodniyReport extends Model{
    protected $table='public.svodniy_report';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'in_gas', 'out_gas', 'skv_job', 'skv_res', 'skv_rem', 'gpa_job', 'gpa_res', 'gpa_rem', 't_in', 't_out', 'p_in', 'p_out', 'config', 'timestamp'
    ];


}

?>
