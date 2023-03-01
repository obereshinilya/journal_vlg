<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rezhim_gpa extends Model{
    protected $table='public.rezhim_gpa';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'number_gpa', 'rezhim', 'timestamp'
    ];


}

?>
