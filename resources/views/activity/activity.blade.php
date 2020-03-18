@extends('templates.default')

@section('content')
    <h1>activity feed</h1>
    @foreach($activity as $a)
        <div class="row">
            @if($a->activity_type == 'wall_post')
                @php
                    $post_user = App\User::where('id', $a->user_id)->first();
                    $wall_user = App\User::where('id', $a->wall_user_id)->first();
                @endphp
                <a class="col-8" href="{{ route('index', $wall_user->username) }}">
                    @if($post_user->id == $wall_user->id)
                            {{ $post_user->getNameOrUsername() }} posted on their profile.
                    @else
                        {{ $post_user->getNameOrUsername() }} posted on
                        {{ $wall_user->getNameOrUsername() }}'s profile.
                    @endif
                </a>
            @elseif($a->activity_type == 'album')
                @php
                    $user = App\User::where('id', $a->user_id)->first();
                    $file_count = App\File::where('album_id', $a->id)->get()->count();
                @endphp
                <a class="col-8"
                        href="{{ route('albums.view', ['username' => $user->username, 'id' => $a->id]) }}">
                    {{ $user->getNameOrusername() }} added {{ $file_count }} files to {{ $a->name }}.
                </a>
            @elseif($a->activity_type == 'file_comment')
                @php
                    $user = App\User::where('id', $a->user_id)->first();
                    $file = App\File::where('id', $a->file_id)->first();
                    $album = App\Album::where('id', $file->album_id)->first();
                    $file_user = App\User::where('id', $album->user_id)->first();
                @endphp
                <a class="col-8"
                        href="{{ route('albums.file.view', [$file_user->username, $album->id, $file->id]) }}">
                    @if($user->id == $file_user->id)
                            {{ $user->getNameOrUsername() }} commented on their file.
                    @else
                        {{ $user->getNameOrUsername() }} commented on
                        {{ $file_user->getNameOrUsername() }}'s file.
                    @endif
                </a>
            @else
                <h2>Invalid activity type</h2>
            @endif
            <p class="col-2">{{ $a->updated_at->isoFormat('MMM d, YYYY') }}<p>
            <p class="col-2">{{ $a->updated_at->isoFormat('h:mm A') }}<p>
        </div>
    @endforeach
@stop
