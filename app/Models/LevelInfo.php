<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelInfo extends Model{
    protected $table='app_info.level_info';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'short_name', 'full_name',
    ];


}

?>
