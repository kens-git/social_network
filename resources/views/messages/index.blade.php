@extends('templates.default')

@section('content')
    @if($user->id != Auth::user()->id)
        @include('templates.profile.header')
    @endif
    @if($messages->count())
        @foreach($messages as $message)
            <a class="conversation"
                    href="{{ route('messages', $user->getWhichIsntMe($message->user1, $message->user2)->username) }}">
                @php
                    $other_user = $user->getWhichIsntMe($message->user1, $message->user2)
                @endphp
                @if(isset($other_user->profile_photo_id))
                    <img class="conversation-profile-picture"
                        src="{{ route('profile_photo', ['id' => $other_user->profile_photo_id]) }}"/>
                @else
                    <img class="conversation-profile-picture" src="{{ route('profile_photo', ['id' => -1]) }}"/>
                @endif
                <div class="conversation-info">
                    <h1>{{ $other_user->getNameOrUsername() }}</h1>
                    <p class="conversation-timestmap">{{ $message->updated_at }}</p>
                    <p class="last-message">{{ $user->getThemOrMe($message->getMessage()->from_user_id) }}: {{ $message->getMessage()->content }}</p>
                </div>
            </a>
        @endforeach
    @else
        <h2>You have no messages.</h2>
    @endif
@stop
