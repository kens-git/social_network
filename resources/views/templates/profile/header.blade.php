@if(isset($cover_file))
    <img width="300" alt="error loading cover picture" class="img-fluid"
        src="{{ route('albums.file', ['username' => $user->username,
             'id' => $cover_file->album_id, 'file_id' => $cover_file->id]) }}">
@endif
<div class="row">
    @if(isset($profile_file))
        <div class="col-4">
            <img width="300" alt="error loading profile picture" class="img-fluid"
                src="{{ route('albums.file', ['username' => $user->username,
                    'id' => $profile_file->album_id, 'file_id' => $profile_file->id]) }}">
        </div>
    @endif
    <div class="col-8">
        <div class="row">
            <h1>{{ $user->first_name }} {{ $user->last_name }}</h1>
        </div>
        <div class="row">
            <p class="col-4">Location: {{ $user->location }}</p>
            <p class="col-4">Email: {{ $user->email }}</p>
        </div>
        <div class="row">
            <p class="col-4">Phone: {{ $user->phone }}</p>
            <p class="col-4">Occupation: {{ $user->occupation }}</p>
        </div>
        <div class="row">
            <p class="col-4">Website: {{ $user->website }}<p>
        </div>
    </div>
</div>
@if(Auth::user()->id != $user->id)
    <ul class="nav navbar justify-content-start">
        <li><a href="{{ route('index', $user->username) }}" class="nav-link">Profile</a></li>
        <li><a href="{{ route('messages', $user->username) }}" class="nav-link">Message</a></li>
        <li><a href="{{ route('albums', $user->username) }}" class="nav-link">Albums</a></li>
    </ul>
@endif
