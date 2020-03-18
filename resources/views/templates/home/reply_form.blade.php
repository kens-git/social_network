<form method="post" class="col-9 align-self-end"
        action="{{ route('post.index', [$user->username, $status->id]) }}">
    @csrf()
    <div class="form-group">
        @error('status')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <textarea name="status{{ $status->id }}"></textarea>
    </div>
    <div class="form-group">
        <input type="submit" value="Post Reply">
    </div>
</form>
