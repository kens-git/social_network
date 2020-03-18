@extends('templates.default')

@section('content')
    @if(Auth::user()->id != $user->id)
        @include('templates.profile.header')
    @endif
    @include('templates.albums.comment_form')
    <div class="row">
        @if(in_array(strtolower($file->extension), ['mov', 'webm', 'mp4']))
            <div class="embed-responsive embed-responsive-16by9">
                <video class="embed-responsive-item"
                    preload="false" src="{{ route('albums.file',
                    ['username' => $user->username,
                    'id' => $file->album_id,
                    'file_id' => $file->id]) }}" controls>
                </video>
            </div>
        @elseif(in_array(strtolower($file->extension), ['jpg', 'jpeg', 'png', 'gif']))
            <img class="img-fluid" src="{{ route('albums.file',
                ['username' => $user->username,
                'id' => $file->album_id,
                'file_id' => $file->id]) }}">
        @else
            <a href="{{ route('albums.file',
                    ['username' => $user->username,
                    'id' => $file->album_id,
                    'file_id' => $file->id]) }}">
                <img width="300" src="{{ asset('icons/file_icon.png') }}">
                <p>{{ $file->name }}</p>
            </a>
        @endif
    </div>
    @foreach($comments as $comment)
        @include('templates.albums.comment')
    @endforeach
@stop
