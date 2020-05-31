@extends('templates.default')

@section('content')
    @include('templates.profile.header')
    <div id="messages-body">
        <div id="linked-conversations">
            @if(isset($last_messages))
                @foreach($last_messages as $message)
                    @php
                        $other_user = $user->getWhichIsntMe($message->user1, $message->user2);
                    @endphp
                    <a href="{{ route('messages', ['username' => $other_user->username]) }}" class="conversation">
                        @if(isset($other_user->profile_photo_id))
                            <img class="conversation-profile-picture"
                                src="{{ route('profile_photo', ['id' => $other_user->profile_photo_id]) }}"/>
                        @else
                            <img class="conversation-profile-picture" src="{{ route('profile_photo', ['id' => -1]) }}"/>
                        @endif
                        <div class="conversation-info">
                            <h1>{{ $other_user->getNameOrUsername() }}</h1>
                            <p class="conversation-timestamp">{{ $message->updated_at }}</p>
                            <p class="last-message">{{ Auth::user()->getThemOrMe($message->getMessage()->from_user_id) }}: {{ $message->getMessage()->content }}</p>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
        <div id="messages">
            @include('templates.messages.send_form')
            @if(isset($messages))
                @foreach($messages as $message)
                    @if($message->from_user_id == Auth::user()->id)
                        <div class="direct-message sent">
                            <p>{{ $message->content }}</p>
                        </div>
                    @else
                        <div class="direct-message received">
                            <p>{{ $message->content }}</p>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@stop
