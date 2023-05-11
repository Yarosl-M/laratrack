@props(['ticket', 'message'])
<div class="message" id="{{$message->id}}">
    <div class="user-info">
        @can('view', $message->user)
        <a href="{{url('/users', [$message->user_id])}}">
        @endcan
            <img class="pfp" src="https://cdn.discordapp.com/attachments/1085284239815217182/1104351697335230564/j01.png">
        @can('view', $message->user)
        </a>
        @endcan
            <p>
                @if (isset($message->user->name))
                    {{$message->user->name}}
                @else
                    {{$message->user->username}}
                @endif
                <i> комментирует ({{$message->created_at->diffForHumans()}})</i></p>
    </div>
    <p>
        {{$message->content}}
    </p>
    @if (!empty(json_decode($message->attachments, true)))
        <p class="attachments-header">Прикреплённые файлы:</p>
        <ul class="bordered">
            @foreach (json_decode($message->attachments, true) as $filename)
                <li>
                    <a href="{{url('/', ['storage', 'tickets', $ticket->id, $message->id, $filename])}}">
                        {{$filename}}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

