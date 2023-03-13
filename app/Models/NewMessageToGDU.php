<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewMessageToGDU extends Model{
    protected $table='chat.new_message_to_gdu';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'count',
    ];


}

?>
