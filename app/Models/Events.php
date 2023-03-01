<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model{
    protected $table='events.event';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'event', 'timestamp', 'option',
    ];


}

?>
