<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ter extends Model{
    protected $table='public.ter';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'metanol_zapas', 'metanol_prihod', 'metanol_rashod',
        'teg_zapas', 'teg_prihod', 'teg_rashod',
        'timestamp', 'yams_yub', 'user'
    ];


}

?>
