<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WallPost extends Model {
    protected $table = 'wall_posts';

    protected $fillable = [
        'wall_user_id',
        'user_id',
        'parent_id',
        'content'
    ];
}
