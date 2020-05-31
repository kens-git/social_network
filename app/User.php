<?php

namespace App;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract {
    use Authenticatable;

    protected $table = 'users';

    protected $hidden = ['password'];

    protected $fillable = [
        'username',
        'password',
        'first_name',
        'last_name',
        'occupation',
        'location',
        'birth_date',
        'phone',
        'email',
        'website',
        'is_admin',
        'profile_photo_id',
        'cover_photo_id',
        'status'];

    public function getName() {
        if($this->first_name && $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }
        if($this->first_name) {
            return $this->first_name;
        }
        return null;
    }

    public function getNameOrUsername() {
        return $this->getName() ?: $this->username;
    }

    public function scopeGetWallPosts($query, int $count = PHP_INT_MAX) {
        return $this->hasMany('App\WallPost')->take($count);
    }

    public function scopeGetWallPostsForUser($query,
            User $user, int $count = PHP_INT_MAX) {
        return $this->hasMany('App\WallPost')
            ->where('user_id', $user->id)->take($count);
    }

    public function getThemOrMe($user_id) {
        if($user_id == $this->id) {
            return "You";
        }
        $user = $this->where('id', $user_id)->first();
        if($user) {
            return $user->first_name ?: $user->username;
        }
        return null;
    }

    public function scopeGetWhichIsntMe($query, $user1_id, $user2_id) {
        if($user1_id == Auth::user()->id) {
            return $this->where('id', $user2_id)->first();
        }
        return $this->where('id', $user1_id)->first();
    }
}
