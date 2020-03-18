<?php

namespace App\Http\Controllers;

use Auth;
use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller {
    public function getIndex() {
        $tasks = Todo::all();
        return view('todo.index')->with('tasks', $tasks);
    }

    public function postIndex(Request $request) {
        $request->validate([
            'title' => 'required|min:1',
            'description' => 'required|min:1'
        ]);

        Todo::create([
            'user_id' => Auth::user()->id,
            'name' => $request->input('title'),
            'description' => $request->input('description'),
            'priority' => Todo::$priority['none']
        ]);

        return redirect()->route('todo');
    }
}
