<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewGUID extends Model{
    protected $table='public.new_guid';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'id', 'old_guid', 'new_guid', 'date', 'check'
    ];


}

?>
