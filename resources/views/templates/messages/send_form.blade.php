<form id="send-message-form" method="post" action="{{ route('messages', ['username' => $user->username]) }}">
    @csrf()
    @error('message')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <textarea name="message"></textarea>
    <br/>
    <input type="submit" value="Send Message">
</form>
