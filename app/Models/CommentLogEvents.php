<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLogEvents extends Model
{
    protected $table = 'public.comment_log_events';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'parent_id', 'event', 'person', 'completion_mark', 'comment_event',
    ];
}
