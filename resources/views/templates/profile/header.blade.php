<img src="" alt="User has no cover image" class="img-fluid">
<div class="row">
    <div class="col-4">
        <img src="" alt="User has no profile image" class="img-fluid">
    </div>
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
