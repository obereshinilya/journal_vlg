<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearBalans extends Model{
    protected $table='public.valoviy_year';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'id', 'month', 'year', 'yams_yub', 'val', 'config'
    ];


}

?>
