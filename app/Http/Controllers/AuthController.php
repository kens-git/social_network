<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getLogin() {
        return view('login');
    }

    public function postLogin(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:12'
        ]);

        if(!Auth::attempt(
                $request->only(['username', 'password']))) {
            return redirect()->back()->withErrors(['error' => 'invalid']);
        }
        return redirect()->route('index');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('index');
    }

    public function getCreate() {
        if(!Auth::user()->is_admin) {
            return redirect()->back();
        }
        return view('create.create');
    }

    public function postCreate(Request $request) {
        if(!Auth::user()->is_admin) {
            return redirect()->back();
        }
        $this->validate($request, [
            'username' => 'required|unique:users',
            'password' => 'same:password-repeat|min:12',
            'password-repeat' => 'min:12'
        ]);
        User::create([
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'is_admin' => $request->input('is-admin') ? true : false
        ]);
        return redirect()->route('create');
    }
}
