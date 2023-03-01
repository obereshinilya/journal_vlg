<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstraGaz extends Model{
    protected $table='public.astragaz';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'date', 'q_ks', 'p_ks', 't_ks', 'q_lu', 'p_lu', 't_lu',
    ];


}

?>
