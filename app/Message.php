<?php

namespace App;

use Auth;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model {
    protected $table = 'messages';

    protected $fillable = [
        'to_user_id',
        'from_user_id',
        'content'
    ];

    public function scopeGetMessagesDesc($query, $user_id) {
        $messages_from = $this->where([
            'from_user_id' => Auth::user()->id,
            'to_user_id' => $user_id
        ])->orderBy('created_at', 'desc')->get();
        $messages_to = $this->where([
            'from_user_id' => $user_id,
            'to_user_id' => Auth::user()->id
        ])->orderBy('created_at', 'desc')->get();
        return $messages_from->merge($messages_to)->sortByDesc('created_at');
    }

    public function scopeGetLastMessages($query) {
        return LastMessage::where('user1', Auth::user()->id)
            ->orWhere('user2', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
    }
}
