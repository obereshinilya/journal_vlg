<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model{
    protected $table='events.user_auth';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'username', 'ip', 'domain_name',
    ];


}

?>
