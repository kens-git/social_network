@extends('templates.default')

@section('content')
    @include('templates.profile.header')
    @if(isset($messages))
        <div id="messages-body">
            <div id="linked-conversations">
                @foreach($last_messages as $message)
                    <a class="conversation"
                            href="{{ route('messages', $user->getWhichIsntMe($message->user1, $message->user2)->username) }}">
                        @if(isset($header_file->id))
                            <img class="conversation-profile-picture"
                                src="{{ route('profile_photo', ['id' => $user->profile_photo_id]) }}"/>
                        @else
                            <img class="conversation-profile-picture" src="{{ route('profile_photo', ['id' => -1]) }}"/>
                        @endif
                        @php
                            $other_user = $user->getWhichIsntMe($message->user1, $message->user2)
                        @endphp
                        <div class="conversation-info">
                            <h1>{{ $other_user->getNameOrUsername() }}</h1>
                            <p class="conversation-timestmap">{{ $message->updated_at }}</p>
                            <p class="last-message">{{ $user->getThemOrMe($message->getMessage()->from_user_id) }}: {{ $message->getMessage()->content }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            <div id="messages">
                @include('templates.messages.send_form')
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
            </div>
        </div>
    @else
        @include('templates.messages.send_form')
    @endif
@stop
