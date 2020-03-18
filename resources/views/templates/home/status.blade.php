<div class="row">
    <div class="col-3">

    </div>
    <div class="col-9">
        @php
            $user = App\User::where('id', $status->user_id)->first();
        @endphp
        <h5>{{ $user->getNameOrUsername() }}</h5>
        <p>{{ $status->content }}</p>
    </div>
</div>