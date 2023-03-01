<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthBalans extends Model{
    protected $table='public.valoviy_month';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'id', 'day', 'month', 'year', 'yams_yub', 'val'
    ];


}

?>
