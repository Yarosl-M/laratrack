@props(['ticket', 'action'])
<div class="action" id="{{$action->id}}">
    @can('view', $action->user)
        <a class="user-pfp-link" href="{{url('/users', [$action->user_id])}}">
    @endcan
    <x-user-pfp :user="($ticket->user)" :size="3"/>
    @can('view', $action->user)
        </a>
    @endcan
    <p>
        @if (isset($action->user->name))
            {{$action->user->name}}
        @else
            {{$action->user->username}}
        @endif
        @php
            use App\Enums\ActionType;
            $attr = $action['attributes'];
        @endphp
        @switch($attr['type'])
            @case(ActionType::TagsChanged->value)
                <i>изменяет теги на</i>
                @foreach ($attr['new_tags'] as $tag)
                    <span class="tag">{{$tag->name}}</span>{{$loop->last ? '' : ', '}}
                @endforeach
                @break
            @case(ActionType::PriorityChanged->value)
                <i>изменяет приоритет на</i>
                <span class="priority">{{$attr['new_priority']->name}}</span>
                @break
            @case(ActionType::TicketAssigned->value)
                <i>назначает ответственным сотрудника</i>
                <a><strong>
                @if (isset($attr['assignee']->name))
                    {{$attr['assignee']->name}}
                @else
                    {{$attr['assignee']->username}}
                @endif
                </strong></a>
                @break
            @case(ActionType::TicketUnassigned->value)
                <i>снимает ответственного сотрудника</i>
                <a><strong>
                @if (isset($attr['last_assignee']->name))
                    {{$attr['last_assignee']->name}}
                @else
                    {{$attr['last_assignee']->username}}
                @endif
                </strong></a>
                @break
            @case(ActionType::TicketClosed->value)
                <i>закрывает тикет</i>
                @break
            @case(ActionType::TicketReopened->value)
                <i>открывает тикет</i>
                @break
            @case(ActionType::FeedbackSent->value)
                <i>оценивает качество обслуживания на данном тикете в </i>
                @for ($i = 0; $i < $attr['rating']; $i++)
                    <img src="{{url('/assets/star_filled.svg')}}" style="width:1.5rem;height:1.5rem;">
                @endfor
                @for ($i = $attr['rating']; $i < 5; $i++)
                    <img src="{{url('/assets/star.svg')}}" style="width:1.5rem;height:1.5rem;">
                @endfor
                @break
            @case(ActionType::TicketArchived->value)
                <i>перемещает тикет в <a href="/tickets/archive">архив</a></i>
                @break
            @default
        @endswitch
        {{-- make a # link and add to css --}}
        <a class="time-link no-underline hover-underline" href="#{{$action->id}}">({{$action->created_at->diffForHumans()}})</a>
    </p>
</div>