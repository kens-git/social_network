<h5>{{ App\User::where('id', $reply->user_id)->first()->getNameOrUsername() }}</h5>
<h4>{{ $reply->content }}</h4>
