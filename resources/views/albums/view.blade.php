@extends('templates.default')

@section('content')
    @if(Auth::user()->username != $user->username)
        @include('templates.profile.header')
    @endif
    @foreach($files as $file)
        @php
            $comment_count = App\FileComment::where('file_id', $file->id)->count();
        @endphp
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
                        'file_id' => $file->id]) }}" class="file-preview-link">
                    <div>
                        <img class="album-image-preview" src="{{ route('albums.file_preview',
                        ['username' => $user->username,
                        'id' => $file->album_id,
                        'file_id' => $file->id]) }}"/>
                        <br/>
                        <p class="file-preview-comment-count">{{ $comment_count }} comments</p>
                        @if($file->user_id == Auth::user()->id)
                            <form action="{{ route('set_profile_photo', ['id' => $file->id]) }}" method="get">
                                @csrf()
                                <button class="set-as-link" type="submit">Set As Profile Picture</button>
                            </form>
                            <form action="{{ route('set_cover_photo', ['id' => $file->id]) }}" method="get">
                                @csrf()
                                <button class="set-as-link" type="submit">Set As Cover Picture</button>
                            </form>
                        @endif
                    </div>
                </a>
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
@stop
