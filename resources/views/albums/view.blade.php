@extends('templates.default')

@section('content')
    @include('templates.profile.header')
    <div class="row">
        @foreach($files as $file)
            <div style="object-fit: contain;" width="300" height="300">
                @if(in_array(strtolower($file->extension), ['mov', 'webm', 'mp4']))
                        <video width="300" height="300" preload="false" src="{{ route('albums.file',
                            ['username' => $user->username,
                            'id' => $file->album_id,
                            'file_id' => $file->id]) }}" controls>
                        </video>
                        <a href="{{ route('albums.file.view',
                            ['username' => $user->username,
                            'id' => $file->album_id,
                            'file_id' => $file->id]) }}">Video Comments</a>
                @elseif(in_array(strtolower($file->extension), ['jpg', 'jpeg', 'png', 'gif']))
                    <a href="{{ route('albums.file.view',
                            ['username' => $user->username,
                            'id' => $file->album_id,
                            'file_id' => $file->id]) }}">
                        <img height="300"src="{{ route('albums.file',
                            ['username' => $user->username,
                            'id' => $file->album_id,
                            'file_id' => $file->id,
                            'is_thumbnail' => true]) }}">
                    </a>
                    @if($file->user_id == Auth::user()->id)
                        <a href="{{ route('profile_photo', ['id' => $file->id]) }}">Set As Profile Picture</a>
                        <a href="{{ route('cover_photo', ['id' => $file->id]) }}">Set As Cover Picture</a>
                    @endif
                @else
                    <a href="{{ route('albums.file.view',
                            ['username' => $user->username,
                            'id' => $file->album_id,
                            'file_id' => $file->id]) }}">
                        <img width="300" src="{{ asset('icons/file_icon.png') }}">
                        <p>{{ $file->name }}</p>
                    </a>
                @endif
            </div>
        @endforeach
    </div>
@stop
