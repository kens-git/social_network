<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'album_id',
        'name',
        'extension',
        'description'
    ];

    public function scopeGetFiles($query, $album_id) {
        return $this->where('album_id', $album_id)->get();
    }
}
