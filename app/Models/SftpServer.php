<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SftpServer extends Model{
    protected $table='public.sftp_server';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'adres_sftp', 'path_sftp', 'user', 'password', 'type'
    ];

}

?>
