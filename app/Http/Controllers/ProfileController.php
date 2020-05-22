<?php

namespace App\Http\Controllers;

use Auth;
use App\Album;
use App\File;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Image;

class ProfileController extends Controller {
    protected function getEditProfile() {
        return view('profile.edit');
    }

    public function postEditProfile(Request $request) {
        $request->validate([
            'password' => 'nullable|same:password-repeat|min:12',
            'password-repeat' => 'nullable|min:12',
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
        if($request->input('password')) {
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
        } else {
            Auth::user()->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'occupation' => $request->input('occupation'),
                'location' => $request->input('location'),
                'birth_date' => $date,
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'website' => $request->input('website')
            ]);
        }

        return redirect()->route('index');
    }

    public function getProfilePhoto($id) {
        $file = File::where('id', $id)->first();
        if(!$file) {
            return $this->getDefaultProfilePhoto();
        }
        $user = User::where('id', $file->user_id)->first();
        if(!$user) {
            // TODO:
            //return 'should be user not found error';
            dd('no user');
            return $this->getDefaultProfilePhoto();
        }
        $path = sprintf('uploads/%d/%d/%d.%s', $user->id, $file->album_id, $file->id, $file->extension);
        //dd($path);
        if(!Storage::exists($path)) {
            // TODO: show error message
            return 'file not found on disk';
        }

        $file = Storage::get($path);
        $image = Image::make($file);
        $image->fit(200, 200);
        return $image->response();
        // $type = Storage::mimeType($path);

        // $headers = ['Content-Type' => Storage::mimeType($path)];
        // $response = Response::make($file, 200, $headers);
    
        // return $response;
    }

    public function getCoverPhoto($id) {
        $file = File::where('id', $id)->first();
        if(!$file) {
            return $this->getDefaultCoverPhoto();
        }
        $user = User::where('id', $file->user_id)->first();
        if(!$user) {
            // TODO:
            //return 'should be user not found error';
            return $this->getDefaultCoverPhoto();
        }
        $path = sprintf('uploads/%d/%d/%d.%s', $user->id, $file->album_id, $file->id, $file->extension);
        //dd($path);
        if(!Storage::exists($path)) {
            // TODO: show error message
            return 'file not found on disk';
        }

        $file = Storage::get($path);
        $image = Image::make($file);
        $image->fit(1200, 300);
        return $image->response();
        // $type = Storage::mimeType($path);

        // $headers = ['Content-Type' => Storage::mimeType($path)];
        // $response = Response::make($image, 200, $headers);
    
        // return $response;
    }

    protected function getDefaultCoverPhoto() {
        $path = 'default_cover.png';
        $file = Storage::get($path);
        $type = Storage::mimeType($path);
        $headers = ['Content-Type' => Storage::mimeType($path)];
        $response = Response::make($file, 200, $headers);
        return $response;
    }

    protected function getDefaultProfilePhoto() {
        $path = 'default_profile.png';
        $file = Storage::get($path);
        $type = Storage::mimeType($path);
        $headers = ['Content-Type' => Storage::mimeType($path)];
        $response = Response::make($file, 200, $headers);
        return $response;
    }

    public function setProfilePhoto($id) {
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

    public function setCoverPhoto($id) {
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
