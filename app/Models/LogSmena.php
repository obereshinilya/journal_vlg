<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSmena extends Model{
    protected $table='events.log_smena';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'name_user', 'start_smena', 'stop_smena',
    ];


}

?>
