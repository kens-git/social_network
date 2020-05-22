<form id="file-comment-form" method="post" class="col-9 align-self-end"
        action="{{ route('post.file.view', [$user->username, $album_id, $file->id]) }}">
    @csrf()
    <div class="form-group">
        @error('comment')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <textarea name="comment"></textarea>
    </div>
    <div class="form-group">
        <input type="submit" value="Post Comment">
    </div>
</form>
