@props(['ticket'])
<div class="ticket-card">
    <a href="/tickets/{{$ticket->id}}" style="display:block;color:black;width:max-content">
        <h2>{{$ticket->subject}}</h2>
    </a>
    <div style="display:flex;flex-direction:row;">
        <a href="">{{$ticket->client()->first()->displayName()}}</a><span style="flex-grow:1;">, {{$ticket->created_at->diffForHumans()}}</span>
        <span style="background-color:var(--primary-color);color:white;border-radius:0.5rem;padding:0.2rem 0.5rem;">
            {{$ticket->is_open ? 'Открыт' : 'Закрыт'}}
        </span>
    </div>
</div>
