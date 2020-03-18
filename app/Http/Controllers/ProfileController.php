<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller {
    protected function getEditProfile() {
        return view('profile.edit');
    }

    protected function postEditProfile(Request $request) {
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
}
