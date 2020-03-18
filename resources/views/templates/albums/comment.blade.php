<div class="row">
    <div class="col-3">

    </div>
    <div class="col-9">
        @php
            $user = App\User::where('id', $comment->user_id)->first();
        @endphp
        <h5>{{ $user->getNameOrUsername() }}</h5>
        <p>{{ $comment->content }}</p>
    </div>
</div>
