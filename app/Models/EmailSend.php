<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSend extends Model
{
    use HasFactory;

    protected $table = 'chat.email_send';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'message', 'theme', 'recepient', 'sender', 'send', 'timestamp',
    ];
}
