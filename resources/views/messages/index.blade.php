@extends('templates.default')

@section('content')
    @if($user->id != Auth::user()->id)
        @include('templates.profile.header')
    @endif
    @foreach($messages as $message)
        @php
            $other_user = $user->getWhichIsntMe($message->user1, $message->user2);
        @endphp
        <a href="{{ route('messages', $other_user->username) }}" class="conversation">
            @if(isset($other_user->profile_photo_id))
                <img class="directory-profile-picture"
                    src="{{ route('profile_photo', ['id' => $other_user->profile_photo_id]) }}"/>
            @else
                <img class="directory-profile-picture" src="{{ route('profile_photo', ['id' => -1]) }}"/>
            @endif
            <div class="conversation-info">
                <h1>{{ $other_user->getNameOrUsername() }}</h1>
                <p class="conversation-timestamp">{{ $message->updated_at }}</p>
                <p class="last-message">{{ $user->getThemOrMe($message->getMessage()->from_user_id) }}: {{ $message->getMessage()->content }}</p>
            </div>
        </a>
    @endforeach
@stop
