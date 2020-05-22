<?php

namespace App\Http\Controllers;

use App\File;
use App\User;
use App\WallPost;
use Auth;
use Image;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getIndex($username = null) {
        if(!$username) {
            $username = Auth::user()->username;
        }
        // why doesn't this work as a User::getUser($username) function?
        $user = User::where('username', $username)->first();
        if(!$user) {
            return view('errors.user_not_found')
                ->with('username', $username);
        }
        $statuses = WallPost::where(
            ['wall_user_id' => $user->id, 'parent_id' => null])->orderBy('updated_at', 'desc')->get();
        //$posts = User::getWallPostsForUser($user);
        $cover_file = File::where('id', $user->cover_photo_id)->first();
        $profile_file = File::where('id', $user->profile_photo_id)->first();
        return view('index')->with(['user' => $user, 'statuses' => $statuses,
            'cover_file' => $cover_file, 'profile_file' => $profile_file]);
    }

    public function postIndex(Request $request, $username, $parent_id = null) {
        $request->validate([
            'status' => 'required|min:1'
        ]);
        $wall_user = User::where('username', $username)->first();
        if(!$wall_user) {
            return 'user doesn\'t exist';
        }

        WallPost::create([
            'wall_user_id' => $wall_user->id,
            'user_id' => Auth::user()->id,
            'parent_id' => $parent_id,
            'content' => $request->input('status')
        ]);
        if($wall_user->id != Auth::user()->id) {
            return redirect()->route('index', [$wall_user->username]);
        }
        return redirect()->route('index');
    }
}
