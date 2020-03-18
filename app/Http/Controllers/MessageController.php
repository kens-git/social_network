<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\LastMessage;
use App\Message;
use App\User;
use Illuminate\Http\Request;

class MessageController extends Controller {
    public function getMessages($username = null) {
        if($username && $username != Auth::user()->username) {
            $other_user = User::where('username', $username)->first();
            if(!$other_user) {
                return view('errors.user_not_found')->with('username', $username);
            }
            $messages = Message::getMessagesDesc($other_user->id);
            if(!$messages->count()) {
                return view('messages.send')->with('user', $other_user);
            }
            return view('messages.send')
                ->with(['user' => $other_user, 'messages' => $messages]);
        }
        $messages = Message::getLastMessages();
        //dd($messages);
        return view('messages.index')
            ->with(['user' => Auth::user(), 'messages' => $messages]);
    }

    public function postMessage(Request $request, $username = null) {
        if($username && $username != Auth::user()->username) {
            $request->validate([
                'message' => 'min:1'
            ]);
            $user = User::where('username', $username)->first();
            if(!$user) {
                return redirect('index')->with('username', $username);
            }
            $message = Message::create([
                'from_user_id' => Auth::user()->id,
                'to_user_id' => $user->id,
                'content' => $request->input('message')
            ]);
            $last = LastMessage::where(['user1' => Auth::user()->id, 'user2' => $user->id])->take(1)->first();
            if(!$last) {
                $last =
                    LastMessage::where(['user1' => $user->id, 'user2' => Auth::user()->id])->take(1)->first();
            }
            if($last) {
                $last->update([
                    'message_id' => $message->id
                ]);
            } else {
                LastMessage::create([
                    'user1' => Auth::user()->id,
                    'user2' => $user->id,
                    'message_id' => $message->id
                ]);
            }
            $messages = Message::getMessagesDesc($user->id);
            return view('messages.send', [$user->username])
                ->with(['user' => $user, 'messages' => $messages]);
        }
        return redirect()->back();
    }
}
