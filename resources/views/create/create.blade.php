@extends('templates.default')

@section('content')
    <form class="form-vertical" method="post" action="{{ route('create') }}">
        @csrf()
        <div class="form-group">
            @error('username')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <input type="text" name="username" placeholder="username">
        </div>
        <div class="form-group">
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <input type="password" name="password" placeholder="password">
        </div>
        <div class="form-group">
            @error('password-repeat')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <input type="password" name="password-repeat" placeholder="Repeat password">
        </div>
        <div class="form-group">
            <input type="checkbox" name="is-admin">
            <label for="is-admin">Is Admin</label>
        </div>
        <div class="form-group">
            <input type="submit" value="Submit">
        </div>
    </form>
@stop
