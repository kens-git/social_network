<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = 'albums';

    protected $fillable = [
        'user_id',
        'name'
    ];

    public function scopeGetAlbums($query, $user_id = null) {
        if($user_id) {
            return $this->where('user_id', $user_id)->get();
        }
        return $this->where('user_id', Auth::user()->id)->get();
    }
}
