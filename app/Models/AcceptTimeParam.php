<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcceptTimeParam extends Model{
    protected $table='app_info.accept_time_param';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'type', 'hour_day', 'date_month',
    ];


}

?>
