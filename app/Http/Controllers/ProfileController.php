<?php

namespace App\Http\Controllers;

use Auth;
use App\Album;
use App\File;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller {
    protected function getEditProfile() {
        return view('profile.edit');
    }

    public function postEditProfile(Request $request) {
        $request->validate([
            'password' => 'same:password-repeat|min:12',
            'password-repeat' => 'min:12',
            'first_name' => 'nullable|min:1',
            'last_name' => 'nullable|min:1',
            'occupation' => 'nullable|min:1',
            'location' => 'nullable|min:1',
            'birth_date' => 'nullable|date_format:Y-m-d',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'website' => 'nullable'
        ]);

        $date = null;
        if($request->input('birth_date')) {
            $date = Carbon::createFromFormat('Y-m-d', $request->input('birth_date'));
        }

        Auth::user()->update([
            'password' => bcrypt($request->input('password')),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'occupation' => $request->input('occupation'),
            'location' => $request->input('location'),
            'birth_date' => $date,
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'website' => $request->input('website')
        ]);

        return redirect()->route('index');
    }

    public function getProfilePhoto($id) {
        $file = File::where('id', $id)->first();
        if(!$file) {
            return 'file doesn\'t exist';
        }
        $album = Album::where('id', $file->album_id)->first();
        if(!$album) {
            return 'album doesn\'t exist';
        }
        if($album->user_id != Auth::user()->id) {
            return 'can\'t set photo from another user\'s album';
        }
        Auth::user()->update(['profile_photo_id' => $id]);
        // return view/route with success message
        return redirect()->back();
    }

    public function getCoverPhoto($id) {
        // factor out with getProfilePhoto()
        $file = File::where('id', $id)->first();
        if(!$file) {
            return 'file doesn\'t exist';
        }
        $album = Album::where('id', $file->album_id)->first();
        if(!$album) {
            return 'album doesn\'t exist';
        }
        if($album->user_id != Auth::user()->id) {
            return 'can\'t set photo from another user\'s album';
        }
        Auth::user()->update(['cover_photo_id' => $id]);
        return redirect()->back();
    }
}
