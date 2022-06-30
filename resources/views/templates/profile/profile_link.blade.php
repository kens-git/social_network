<a href="{{ route('index', ['username' => $user->username]) }}" class="directory-link">
    <div class="directory-link-content">
        @if(isset($user->profile_photo_id))
            <img class="directory-profile-picture"
                src="{{ route('profile_photo', ['id' => $user->profile_photo_id]) }}"/>
        @else
            <img class="directory-profile-picture" src="{{ route('profile_photo', ['id' => -1]) }}"/>
        @endif
        <div class="directory-user-info">
            <h1>{{ $user->getNameOrUsername() }}</h1>
            <p class="directory-last-login">Last login: 4:00 PM, April 11, 2020</p>
        </div>
    </div>
</a>
