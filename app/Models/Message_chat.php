<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message_chat extends Model{
    protected $table='chat.message';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'user_sender', 'user_recipent', 'message', 'timestamp', 'object', 'is_read', 'type_message', 'file', 'name_group', 'color_message', 'group'
    ];

}

?>
