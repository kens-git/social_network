@extends('templates.default')

@section('content')
    <h3 style="margin-top: 30px; margin-bottom: 20px;">Submit a site feature or bug fix request:</h3>
    <form class="form-vertical" method="post" action="{{ route('todo') }}">
        @csrf()
        <div class="form-group">
            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <input type="text" name="title" placeholder="Suggestion Name">
        </div>
        <div class="form-group">
            @error('description')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <textarea name="description" placeholder="Description"></textarea>
        </div>
        <div class="form-group">
            <input type="submit" value="Submit">
        </div>
    </form>
    <h2 style="margin-top: 50px; margin-bottom: 15px;">Current Todo List:</h2>
    <h3 style="color: rgb(255, 75, 75); text-decoration: underline;">Critical</h3>
    @foreach($tasks as $task)
        @if($task->priority == App\Todo::$priority['critical'])
            <div class="container">
                <h4>{{ $task->name }}</h4>
                <p>{{ $task->description }}</p>
            </div>
        @endif
    @endforeach
    <h3 style="color: rgb(255, 125, 25); text-decoration: underline;">High Priority</h3>
    @foreach($tasks as $task)
        @if($task->priority == App\Todo::$priority['high'])
            <div class="container">
                <h4>{{ $task->name }}</h4>
                <p>{{ $task->description }}</p>
            </div>
        @endif
    @endforeach
    <h3 style="color: rgb(175, 175, 0); text-decoration: underline;">Medium Priority</h3>
    @foreach($tasks as $task)
        @if($task->priority == App\Todo::$priority['medium'])
            <div class="container">
                <h4>{{ $task->name }}</h4>
                <p>{{ $task->description }}</p>
            </div>
        @endif
    @endforeach
    <h3 style="color: rgb(75, 255, 75); text-decoration: underline;">Low Priority</h3>
    @foreach($tasks as $task)
        @if($task->priority == App\Todo::$priority['low'])
            <div class="container">
                <h4>{{ $task->name }}</h4>
                <p>{{ $task->description }}</p>
            </div>
        @endif
    @endforeach
    <h3 style="text-decoration: underline;">Pending Suggestions</h3>
    @foreach($tasks as $task)
        @if($task->priority == App\Todo::$priority['none'])
            <div class="container">
                <h4>{{ $task->name }}</h4>
                <p>{{ $task->description }}</p>
            </div>
        @endif
    @endforeach
    <h3 style="text-decoration: underline;">Completed Tasks</h3>
    @foreach($tasks as $task)
        @if($task->priority == App\Todo::$priority['completed'])
            <div class="container">
                <h4>{{ $task->name }}</h4>
                <p>{{ $task->description }}</p>
            </div>
        @endif
    @endforeach
@stop
