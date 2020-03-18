<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class DirectoryController extends Controller {
    public function getDirectory() {
        $users = User::all()->sortBy('last_name');
        return view('directory.directory')->with('users', $users);
    }
}
