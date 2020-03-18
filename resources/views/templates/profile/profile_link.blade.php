<div class="media">
    <a class="pull-left" href="{{ route('index', ['username' => $user->username]) }}">
        <img class="media-object" alt="No Profile Picture" src="">
    </a>
    <div class="media-body">
        <h4 class="media-heading">
            <a href="{{ route('index', ['username' => $user->username]) }}">
                @if(isset($user->last_name))
                    {{ $user->first_name }} {{ $user->last_name }}
                @else
                    No Name
                @endif    
            </a>
        </h4>
        @if($user->location)
            <p>{{ $user->location }}</p>
        @endif
    </div>
</div>
