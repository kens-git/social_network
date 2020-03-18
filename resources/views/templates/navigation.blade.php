<nav class="navbar justify-content-start">
    <a class="nav-link" href="{{ route('index') }}">Home</a>
    <a class="nav-link" href="{{ route('activity') }}">Activity</a>
    <a class="nav-link" href="{{ route('messages') }}">Messages</a>
    <a class="nav-link" href="{{ route('albums') }}">Albums</a>
    <a class="nav-link" href="{{ route('directory') }}">Directory</a>
    <a class="nav-link" href="{{ route('profile.edit') }}">Edit Profile</a>
    <a class="nav-link" href="{{ route('logout') }}">Logout</a>
    {{-- Commented out search bar --}}
    {{-- <form class="form-inline my-2 my-lg-0 ml-auto">
        <input class="form-control mr-sm-2" type="search" placeholder="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form> --}}
</nav>
