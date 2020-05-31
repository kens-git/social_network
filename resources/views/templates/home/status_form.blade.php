<form id="wall-post-form" method="post" action="{{ route('index', ['username' => $user->username]) }}">
    @csrf()
    <textarea name="status" placeholder="Post on {{ $user->getNameOrUsername() }}'s profile"></textarea>
    <input type="submit" id="wall-post-button" name="post_button" value="Post">
    <input type="submit" id="status-post-button" name="status_button" value="Update Status">
</form>
