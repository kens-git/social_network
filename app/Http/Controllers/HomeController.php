<?php

namespace App\Http\Controllers;

use App\Album;
use App\File;
use App\FileComment;
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
        $wall_posts = WallPost::where(['user_id' => $user->id])->take(10)->get();
        foreach($wall_posts as &$post) {
            $post['activity_type'] = 'wall_post';
        }
        $albums = Album::where(['user_id' => $user->id])->take(10)->get();
        foreach($albums as &$album) {
            $album['activity_type'] = 'album';
        }
        $file_comments = FileComment::where(['user_id' => $user->id])->take(10)->get();
        foreach($file_comments as &$comment) {
            $comment['activity_type'] = 'file_comment';
        }
        $activity = collect();
        $activity = $activity->merge($wall_posts)->merge($albums)->merge($file_comments)
            ->sortByDesc('created_at');
        return view('index')->with(['user' => $user, 'statuses' => $statuses,
            'cover_file' => $cover_file, 'profile_file' => $profile_file,
            'activity' => $activity]);
    }

    public function postIndex(Request $request, $username, $parent_id = null) {
        $request->validate([
            'status' => 'required|min:1'
        ]);
        $wall_user = User::where('username', $username)->first();
        if(!$wall_user) {
            return 'user doesn\'t exist';
        }

        if($request->post_button) {
            WallPost::create([
                'wall_user_id' => $wall_user->id,
                'user_id' => Auth::user()->id,
                'parent_id' => $parent_id,
                'content' => $request->input('status')
            ]);
        } else if($request->status_button && $wall_user == Auth::user()) {
            Auth::user()->update(['status' => $request->input('status')]);
        }
        if($wall_user->id != Auth::user()->id) {
            return redirect()->route('index', [$wall_user->username]);
        }
        return redirect()->route('index');
    }
}
