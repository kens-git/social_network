<form method="post" action="{{ route('index', ['username' => $user->username]) }}">
    @csrf()
    <div class="form-group">
        @error('status')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <textarea name="status"></textarea>
    </div>
    <div class="form-group">
        <input type="submit" value="Post Status">
    </div>
</form>
