@extends('templates.default')

@section('content')
    @include('templates.profile.header')
    @if(isset($messages))
        @include('templates.messages.send_form')
        @foreach($messages as $message)
            @if($message->from_user_id == Auth::user()->id)
                <p>{{ $message->content }}</p>
            @else
                <p>{{ $message->content }}</p>
            @endif
        @endforeach
    @else
        @include('templates.messages.send_form')
    @endif
@stop
