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
            @php
                $file_count = App\File::where('album_id', $album->id)->count();
            @endphp
            <a href="{{ route('albums.view', ['username' => $user->username, 'id' => $album->id]) }}" class="album-link">
                <div>
                    <p class="album-name">{{ $album->name }}</p>
                    <p class="album-file-count">
                        - {{ $file_count }} files, {{ App\Album::getTotalCommentCount($album->id) }} comments</p>
                    <p class="album-creation-timestamp">Created: {{ $album->created_at }}</p>
                    <p class="album-updated-timestamp">Updated: {{ $album->updated_at }}</p>
                </div>
            </a>  
        @endforeach
    @else
        <h1>No albums to display.</h1>
    @endif
@stop
