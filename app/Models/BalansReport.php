<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalansReport extends Model{
    protected $table='public.balans_report';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'id', 'guid', 'value', 'date', 'text'
    ];


}

?>
