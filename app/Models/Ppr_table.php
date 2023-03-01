<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppr_table extends Model{
    protected $table='public.ppr_table';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'object', 'plan_begin', 'plan_end', 'fact_begin', 'fact_end', 'type_job', 'comment'
    ];

}

?>
