<div id="profile-header">
    @if(isset($cover_file->id))
        <img id="cover-photo" src="{{ route('cover_photo', ['id' => $cover_file->id]) }}"/>
    @else
        <img id="cover-photo" src="{{ route('cover_photo', ['id' => -1]) }}"/>
    @endif
    @if(isset($profile_file->id))
        <a href="{{ route('index', ['username' => $user->username]) }}"><img id="profile-photo"
            src="{{ route('profile_photo', ['id' => $profile_file->id]) }}"/></a>
    @else
        <a href="{{ route('index', ['username' => $user->username]) }}"><img id="profile-photo"
            src="{{ route('profile_photo', ['id' => -1]) }}"/></a>
    @endif
    <h1 id="header-name-label">{{ $user->getNameOrUsername() }}</h1>
    <div id="profile-links">
        <a href="{{ route('index', $user->username) }}">Profile</a>
        <a href="{{ route('messages', $user->username) }}">Message</a>
        <a href="{{ route('albums', $user->username) }}">Albums</a>
    </div>
</div>
