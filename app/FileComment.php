<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileComment extends Model
{
    protected $table = 'file_comments';

    protected $fillable = [
        'user_id',
        'file_id',
        'content'
    ];
}
