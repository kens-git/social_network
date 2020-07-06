<form id="new-album-form" method="post" action="{{ route('albums') }}" enctype="multipart/form-data">
    @csrf()
    <div class="form-group">
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <input type="text" name="name" placeholder="Album Name">
    </div>
    <div class="form-group">
        @error('files')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <input type="file" name="files[]" multiple>
    </div>
    <div class="form-group">
        <input type="submit" value="Create Album">
    </div>
</form>
