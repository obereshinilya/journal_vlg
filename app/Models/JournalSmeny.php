<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalSmeny extends Model{
    protected $table='public.journal_smeny';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'id_record', 'val', 'date', 'color_text', 'text_weight',
    ];

}

?>
