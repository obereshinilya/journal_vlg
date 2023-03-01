<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToAlpha extends Model{
    protected $table='public.data_to_alpha';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'tag_name', 'value', 'name_param', 'short_name',
    ];


}

?>
