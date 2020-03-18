@extends('templates.default')

@section('content')
    @include('templates.profile.header')
    @if(isset($messages))
        @include('templates.messages.send_form')
        @foreach($messages as $message)
            @if($message->from_user_id == Auth::user()->id)
                <p class="col-6"
                        style="background-color: rgb(150, 150, 255); text-align: right; border-radius: 5px;">
                    {{ $message->content }}
                </p>
            @else
                <p class="col-6" style="background-color: rgb(130, 255, 130); border-radius: 5px;">
                    {{ $message->content }}
                </p>
            @endif
        @endforeach
    @else
        @include('templates.messages.send_form')
    @endif
@stop
