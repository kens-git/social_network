@extends('templates.default')

@section('content')
<h3>Update Profile</h3>
    <div class="row">
        <div class="col-lg-6">
            <form class="form-vertical" role="form" method="post" action="{{ route('profile.edit') }}">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" id="username"
                        disabled="true" value="{{ Auth::user()->username }}  -  username can't be modified.">
                </div>
                <div class="form-group">
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="password" name="password" class="form-control" id="password"
                        placeholder="New Password (Optional)">
                </div>
                <div class="form-group">
                    @error('password-repeat')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="password" name="password-repeat" class="form-control" id="password-repeat"
                        placeholder="Password Repeat">
                </div>
                <div class="form-group">
                    @error('first_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="text" name="first_name" class="form-control" id="first_name"
                        placeholder="First Name"
                        value="{{ Request::old('first_name') ?: Auth::user()->first_name }}">
                </div>
                <div class="form-group">
                    @error('last_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="text" name="last_name" class="form-control" id="last_name"
                        placeholder="Last Name"
                        value="{{ Request::old('last_name') ?: Auth::user()->last_name }}">
                </div>
                <div class="form-group">
                    @error('location')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="text" name="location" class="form-control" id="location"
                        placeholder="Location"
                        value="{{ Request::old('location') ?: Auth::user()->location }}">
                </div>
                <div class="form-group">
                    @error('occupation')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="text" name="occupation" class="form-control" id="occupation"
                        placeholder="Occupation"
                        value="{{ Request::old('occupation') ?: Auth::user()->occupation }}">
                </div>
                <div class="form-group">
                    @error('birth_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="text" name="birth_date" class="form-control" id="birth_date"
                        placeholder="Birthday: YYYY-MM-DD"
                        value="{{ Request::old('birth_date') ?: Auth::user()->birth_date }}">
                </div>
                <div class="form-group">
                    @error('phone')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="text" name="phone" class="form-control" id="phone"
                        placeholder="Phone: 123-456-7890"
                        value="{{ Request::old('phone') ?: Auth::user()->phone }}">
                </div>
                <div class="form-group">
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="text" name="email" class="form-control" id="email"
                        placeholder="E-mail"
                        value="{{ Request::old('email') ?: Auth::user()->email }}">
                </div>
                <div class="form-group">
                    @error('website')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="text" name="website" class="form-control" id="website"
                        placeholder="Website"
                        value="{{ Request::old('website') ?: Auth::user()->website }}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Update</button>
                </div>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
        </div>
    </div>
@stop