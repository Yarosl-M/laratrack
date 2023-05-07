@props(['ticket', 'message'])
<div class="message">
    <div class="user-info" id="{{$message->id}}">
        @can('view', $message->user)
        <a href="{{url('/users', [$message->user_id])}}">
        @endcan
            <img class="pfp" src="https://cdn.discordapp.com/attachments/1085284239815217182/1104351697335230564/j01.png">
        @can('view', $message->user)
        </a>
        @endcan
            <p>
                @if (isset($message->user->name))
                    $message->user->name
                @else
                    $message->user->username
                @endif
                <i> создаёт тикет ({{$message->created_at->diffForHumans()}})</i></p>
    </div>
    <p>
        {{$message->content}}
    </p>
    @if (!empty(json_decode($message->attachments)))
        <p class="attachments-header">Прикреплённые файлы:</p>
        <ul class="bordered">
            @foreach (json_decode($message->attachments) as $filename)
                <li>
                    <a href="{{url('/', ['files', 'tickets', $ticket->id, $message->id, $filename])}}">
                        {{$filename}}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>