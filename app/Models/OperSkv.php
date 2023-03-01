<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperSkv extends Model{
    protected $table='public.oper_skv';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'id_td', 'text', 'timestamp', 'edit_at', 'content_editable'
    ];


}

?>
