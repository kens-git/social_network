<form method="post" action="{{ route('messages', ['username' => $user->username]) }}">
    @csrf()
    <div class="form-group">
        @error('message')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <textarea name="message"></textarea>
    </div>
    <div class="form-group">
        <input type="submit" value="Send Message">
    </div>
</form>
