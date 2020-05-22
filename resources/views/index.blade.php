@extends('templates.default')

@section('content')
    @include('templates.profile.header')
    <div id="wall">
        <div id="wall-sidebar">
            <div id="info-section">
                <h1 class="section-header">Bio</h1>
                <h2 class="info-section-status"">Insert Status Here</h2>
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
