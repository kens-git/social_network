@extends('templates.default')

@section('content')
    @foreach($activity as $a)
            @if($a->activity_type == 'wall_post')
                @php
                    $post_user = App\User::where('id', $a->user_id)->first();
                    $wall_user = App\User::where('id', $a->wall_user_id)->first();
                @endphp
                <a href="{{ route('index', $wall_user->username) }}">
                    <div class="site-activity">
                        <h1 class="activity-info">
                            @if($post_user->id == $wall_user->id)
                                    <b>{{ $post_user->getNameOrUsername() }}</b> posted on their profile.
                            @else
                                {{ $post_user->getNameOrUsername() }} posted on
                                {{ $wall_user->getNameOrUsername() }}'s profile.
                            @endif
                        </h1>
                        <h2 class="activity-timestamp">{{ $a->updated_at }}</h2>
                    </div>
                </a>
            @elseif($a->activity_type == 'album')
                @php
                    $user = App\User::where('id', $a->user_id)->first();
                    $file_count = App\File::where('album_id', $a->id)->get()->count();
                @endphp
                <a href="{{ route('albums.view', ['username' => $user->username, 'id' => $a->id]) }}">
                    <div class="site-activity">
                        <h1 class="activity-info">
                            {{ $user->getNameOrusername() }} added {{ $file_count }} files to {{ $a->name }}.
                        </h1>
                        <h2 class="activity-timestamp">{{ $a->updated_at }}</h2>
                    </div>
                </a>
            @elseif($a->activity_type == 'file_comment')
                @php
                    $user = App\User::where('id', $a->user_id)->first();
                    $file = App\File::where('id', $a->file_id)->first();
                    $album = App\Album::where('id', $file->album_id)->first();
                    $file_user = App\User::where('id', $album->user_id)->first();
                @endphp
                <a href="{{ route('albums.file.view', [$file_user->username, $album->id, $file->id]) }}">
                    <div class="site-activity">
                        <h1 class="activity-info">
                            @if($user->id == $file_user->id)
                                    {{ $user->getNameOrUsername() }} commented on their file.
                            @else
                                {{ $user->getNameOrUsername() }} commented on
                                {{ $file_user->getNameOrUsername() }}'s file.
                            @endif
                        </h1>
                        <h2 class="activity-timestamp">{{ $a->updated_at }}</h2>
                    </div>
                </a>
            @endif
    @endforeach
@stop
