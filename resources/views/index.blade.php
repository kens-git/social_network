@extends('templates.default')

@section('content')
    @include('templates.profile.header')
    @include('templates.home.status_form')
    @foreach($statuses as $status)
        @include('templates.home.status')
        {{-- @include('templates.home.reply_form') --}}
        @php
            $replies = App\WallPost::where('parent_id', $status->id)->orderBy('updated_at', 'desc')->get();
        @endphp
        @foreach($replies as $reply)
            @include('templates.home.reply')
        @endforeach
    @endforeach
@stop
