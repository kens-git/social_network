@extends('templates.default')

@section('content')
    @if($users->count())
        <div class="row">
            <div class="col-lg-12">
                <h2 id="directory-user-count">{{ $users->count() }} users.</h2>
                @foreach($users as $user)
                    @include('templates.profile.profile_link')
                @endforeach
            </div>
        </div>
    @else

    @endif
@stop
