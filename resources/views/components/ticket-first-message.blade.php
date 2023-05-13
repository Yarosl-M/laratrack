@props(['ticket', 'message'])
<div class="message" id="{{$message->id}}">
    <div class="user-info">
        @can('view', $message->user)
        <a class="user-pfp-link" href="{{url('/users', [$message->user_id])}}">
        @endcan
        <x-user-pfp :user="$message->user" :size="3"/>
        @can('view', $message->user)
        </a>
        @endcan
            <p>
                @if (isset($message->user->name))
                    {{$message->user->name}}
                @else
                    {{$message->user->username}}
                @endif
                <i> создаёт тикет </i><a class="time-link no-underline hover-underline" href="#{{$message->id}}">
                    ({{$message->created_at->diffForHumans()}})</a>
                </p>
    </div>
    <p>
        {{$message->content}}
    </p>
    @if (!empty(json_decode($message->attachments, true)))
        <p class="attachments-header">Прикреплённые файлы:</p>
        <ul class="bordered">
            @foreach (json_decode($message->attachments, true) as $filename)
                <li>
                    <a target="_blank" href="{{url('/', ['storage', 'tickets', $ticket->id, $message->id, $filename])}}">
                        {{$filename}}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>