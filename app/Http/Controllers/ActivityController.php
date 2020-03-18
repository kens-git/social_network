<?php

namespace App\Http\Controllers;

use App\Album;
use App\FileComment;
use App\WallPost;
use Illuminate\Http\Request;

class ActivityController extends Controller {
    public function getActivity() {
        $wall_posts = WallPost::all()->take(100);
        foreach($wall_posts as &$post) {
            $post['activity_type'] = 'wall_post';
        }
        $albums = Album::all()->take(100);
        foreach($albums as &$album) {
            $album['activity_type'] = 'album';
        }
        $file_comments = FileComment::all()->take(100);
        foreach($file_comments as &$comment) {
            $comment['activity_type'] = 'file_comment';
        }
        $activity = collect();
        $activity = $activity->merge($wall_posts)->merge($albums)->merge($file_comments)
            ->sortByDesc('created_at');
        return view('activity.activity')->with('activity', $activity);
    }
}
