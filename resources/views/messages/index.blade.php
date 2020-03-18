@extends('templates.default')

@section('content')
    @if($user->id != Auth::user()->id)
        @include('templates.profile.header')
    @endif
    @if($messages->count())
        @foreach($messages as $message)
            <a class="row"
                    href="{{ route('messages', $user->getWhichIsntMe($message->user1, $message->user2)->username) }}">
                @php
                    $other_user = $user->getWhichIsntMe($message->user1, $message->user2)
                @endphp
                <h1>{{ $other_user->getNameOrUsername() }}</h1>
                <h3>{{ $user->getThemOrMe($message->getMessage()->from_user_id) }}: {{ $message->getMessage()->content }}</h3>
            </a>
        @endforeach
    @else
        <h2>You have no messages.</h2>
    @endif
@stop
