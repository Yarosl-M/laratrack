{{-- Show a single ticket page --}}
@php
    use App\Models\User;
    use App\Models\Message;
    use App\Models\Ticket;
    use App\Models\ThreadAction;
@endphp
@props(['ticket', 'entries'])
<x-layout :stylesheets="$sheets" :title="$title">
    <section class="main" style="margin:0;">
        <h1>
            {{$ticket->subject}}
        </h1>
        <section class="ticket-items">
            @foreach ($entries as $e)
            @if ($e::class === Message::class)
                @if ($loop->first)
                    <x-ticket-first-message :ticket="$ticket" :message="$e"/>
                @else
                    <x-ticket-message :ticket="$ticket" :message="$e"/>
                @endif
            @else
                <x-ticket-action :ticket="$ticket" :action="$e"/>
            @endif
        @endforeach
        </section>
        @unless ($ticket->archived_to != null)
            <x-message-form :ticket="$ticket"/>    
        @endunless
    </section>
    <section class="sidebar">
        <h3>Сведения о тикете</h3>
        <i>Клиент:</i>
        <div class="sidebar-client">
            <x-user-pfp :user="($ticket->client)" :size="3"/>
            {{$ticket->client->displayName()}}
        </div>
        @if ($ticket->priority ?? false)
            <i>Приоритет:</i>
            <div class="sidebar-priority">
                <b>{{$ticket->priority->name}}</b>
            </div>
        @endif
        <i>Теги:</i>
        <div class="sidebar-tags">
            @if (count($ticket->tags) == 0)
                Теги отсутствуют.
            @else
                @foreach ($ticket->tags as $tag)
                    <span class="tag">{{$tag->name}}</span>
                @endforeach
            @endif
        </div>
        @if ($ticket->assigned_to ?? false)
            <i>Ответственный сотрудник:</i>
            <div class="sidebar-assignee">
                <x-user-pfp :user="($ticket->assignee)" :size="3"/>
                {{$ticket->assignee->displayName()}}
            </div>
        @else
        <span>
            Ответственный сотрудник не назначен
        </span>
        @endif
        @unless ($ticket->archived_to != null)
            <a class="sidebar-link" href="/tickets/{{$ticket->id}}/settings">Параметры тикета…</a>
            <a href="#" class="sidebar-link">В архив</a>
            <a href="#" class="sidebar-link link-delete">Удалить тикет</a>
        @endunless
    </section>
</x-layout>