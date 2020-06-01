@extends('templates.default')

@section('content')
    @if(Auth::user()->id != $user->id)
        @include('templates.profile.header')
    @endif
    <div class="row">
        @if(in_array(strtolower($file->extension), ['mov', 'webm', 'mp4']))
            <video
                preload="false" src="{{ route('albums.file',
                ['username' => $user->username,
                'id' => $file->album_id,
                'file_id' => $file->id]) }}" controls>
            </video>
        @elseif(in_array(strtolower($file->extension), ['jpg', 'jpeg', 'png', 'gif']))
            <a href="{{ route('albums.file.full_size', 
                    ['username' => $user->username,
                    'id' => $file->album_id,
                    'file_id' => $file->id]) }}">
                <img id="file-image" src="{{ route('albums.file',
                    ['username' => $user->username,
                    'id' => $file->album_id,
                    'file_id' => $file->id]) }}">
            </a>
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
    @include('templates.albums.comment_form')
    @foreach($comments as $comment)
        @include('templates.albums.comment')
    @endforeach
@stop
