<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataToAlpha extends Model{
    protected $table='public.data_to_alpha';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'id', 'tagname', 'value', 'description'
    ];


}

?>
