@extends('templates.default')

@section('content')
    @if($user->id != Auth::user()->id)
        @include('templates.profile.header')
    @endif
    @if($user->id == Auth::user()->id)
        @include('templates.albums.new_album')
    @endif
    @if(isset($albums))
        @foreach($albums as $album)
            <a href="{{ route('albums.view', ['username' => $user->username, 'id' => $album->id]) }}">
                {{ $album->name }}
            </a>
        @endforeach
    @else
        <h1>No albums to display.</h1>
    @endif
@stop
