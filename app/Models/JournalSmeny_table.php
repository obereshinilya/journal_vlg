<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalSmeny_table extends Model{
    protected $table='public.journal_smeny_insert_table';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'name_table', 'date', 'timestamp','oborudovanie','status', 'color_back', 'color_text', 'on_print',
    ];

}

?>
