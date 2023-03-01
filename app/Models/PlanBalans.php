<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanBalans extends Model{
    protected $table='public.valoviy_plan';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'id', 'year', 'plan_year', 'yams_yub'
    ];


}

?>
