<?php

namespace App;

use App\Message;
use Illuminate\Database\Eloquent\Model;

class LastMessage extends Model
{
    protected $table = 'last_messages';

    protected $fillable = [
        'user1',
        'user2',
        'message_id'
    ];

    public function getMessage() {
        return Message::where('id', $this->message_id)->first();
    }
}
