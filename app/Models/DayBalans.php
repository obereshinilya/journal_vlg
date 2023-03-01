<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayBalans extends Model{
    protected $table='public.valoviy_day';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'id', 'hour', 'day', 'month', 'year', 'yams_yub', 'val'
    ];


}

?>
