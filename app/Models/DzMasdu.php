<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DzMasdu extends Model{
    protected $table='public.dz_masdu';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'dz', 'autor', 'create', 'check', 'comment', 'info'
    ];

}

?>
