@php
    $user = App\User::where('id', $comment->user_id)->first();
@endphp
<a href="{{ route('index', ['username' => $user->username]) }}" class="file-comment">
    @if(isset($profile_file->id))
        <img class="file-comment-profile-picture"
            src="{{ route('profile_photo', ['id' => $user->profile_photo_id]) }}"/>
    @else
        <img class="file-comment-profile-picture" src="{{ route('profile_photo', ['id' => -1]) }}"/>
    @endif
    <div class="file-comment-info">
        <h1>{{ $user->getNameOrUsername() }}</h1>
        <p class="file-comment-timestamp">{{ $comment->updated_at }}</p>
        <p class="file-comment-message">{{ $comment->content }}</p>
    </div>
</a>
