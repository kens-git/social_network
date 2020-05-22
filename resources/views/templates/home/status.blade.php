<!-- <div class="row">
    <div class="col-3">

    </div>
    <div class="col-9">
        @php
            $user = App\User::where('id', $status->user_id)->first();
        @endphp
        <h5>{{ $user->getNameOrUsername() }}</h5>
        <p>{{ $status->content }}</p>
    </div>
</div> -->

<div class="wall-post">
    @php
        $user = App\User::where('id', $status->user_id)->first();
    @endphp
    <div class="wall-post-header">
        <a href="{{ route('index', ['username' => $user->username]) }}">
            @if(isset($header_file->id))
                <img class="wall-post-profile-picture"
                    src="{{ route('profile_photo', ['id' => $user->profile_photo_id]) }}"/>
            @else
                <img class="wall-post-profile-picture" src="{{ route('profile_photo', ['id' => -1]) }}"/></a>
            @endif
        </a>
        <div class="wall-post-info">
            <h1>{{ $user->getNameOrUsername() }}</h1>
            <p class="wall-post-timestamp">{{ $status->updated_at }}</p>
        </div>
    </div>
    <p class="wall-post-text">{{ $status->content }}</p>
</div>