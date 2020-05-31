@extends('templates.default')

@section('content')
    @include('templates.profile.header')
    <div id="wall">
        <div id="wall-sidebar">
            <div id="info-section">
                <h1 class="section-header">Bio</h1>
                <h2 class="info-section-status">{{ $user->status }}</h2>
                <h2 class="info-section-header">Location</h2>
                <h2 class="info-section-data">{{ $user->location }}</h2>
                <h2 class="info-section-header">Occupation</h2>
                <h2 class="info-section-data">{{ $user->occupation }}</h2>
                <h2 class="info-section-header">Birthdate</h2>
                <h2 class="info-section-data">{{ $user->birth_date }}</h2>
                <h2 class="info-section-header">Phone</h2>
                <h2 class="info-section-data">{{ $user->phone }}</h2>
                <h2 class="info-section-header">E-mail</h2>
                <h2 class="info-section-data">{{ $user->email }}</h2>
                <h2 class="info-section-header">Website</h2>
                <h2 class="info-section-data">{{ $user->website }}</h2>
            </div>
            <div id="recent-activity-section">
                <h1 class="section-header">Recent Activity</h1>
                @foreach($activity as $a)
                    @if($a->activity_type == 'wall_post')
                        @php
                            $post_user = App\User::where('id', $a->user_id)->first();
                            $wall_user = App\User::where('id', $a->wall_user_id)->first();
                        @endphp
                        <a href="{{ route('index', ['username' => $wall_user->username]) }}">
                            <div class="recent-activity">
                                <h2>
                                @if($post_user->id == $wall_user->id)
                                    Posted on their profile.
                                @else
                                    Posted on <b>{{ $wall_user->getNameOrUsername() }}'s</b> profile.
                                @endif
                                </h2>
                                <h3>{{ $a->updated_at }}</h3>
                            </div>
                        </a>
                    @elseif($a->activity_type == 'album')
                        @php
                            $user = App\User::where('id', $a->user_id)->first();
                            $file_count = App\File::where('album_id', $a->id)->get()->count();
                        @endphp
                        <a href="{{ route('albums.view', ['username' => $user->username, 'id' => $a->id]) }}">
                            <div class="recent-activity">
                                <h2>
                                    Added <b>{{ $file_count }}</b> files to <b>{{ $a->name }}</b>.
                                </h2>
                                <h3>{{ $a->updated_at }}</h3>
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
                            <div class="recent-activity">
                                <h2>
                                @if($user->id == $file_user->id)
                                    Commented on their file.
                                @else
                                    Commented on <b>{{ $file_user->getNameOrUsername() }}'s</b> file.
                                @endif
                                </h2>
                                <h3>{{ $a->updated_at }}</h3>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
        <div id="wall-posts">
            <h1 class="section-header">Posts</h1>
            @include('templates.home.status_form')
            @foreach($statuses as $status)
                @include('templates.home.status')
                @php
                    $replies = App\WallPost::where('parent_id', $status->id)->orderBy('updated_at', 'desc')->get();
                @endphp
                @foreach($replies as $reply)
                    @include('templates.home.reply')
                @endforeach
            @endforeach
        </div>
    </div>
@stop
