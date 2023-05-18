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
            @can('send_message', $ticket)
                <x-message-form :ticket="$ticket"/>    
            @endcan
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
            @can('change_params', $ticket)
                <a class="sidebar-link" href="/tickets/{{$ticket->id}}/settings">Параметры тикета…</a>
            @endcan
            @can('archive', $ticket)
                <a href="#" class="sidebar-link" id="link-archive">В архив</a>                
            @endcan
            @can('delete', $ticket)
                <a href="#" class="sidebar-link link-delete" id="link-delete">Удалить тикет</a>                
            @endcan
        @endunless
    </section>
</x-layout>
@can('archive', $ticket)
    <dialog id="archive-dialog">
        <form method="POST" action="{{url()->current()}}/archive" class="dialog-wrapper">
            @csrf
            <h2>Архивирование тикета</h2>
            <p>Вы уверены, что хотите переместить тикет в архив?<br>Тикеты в архиве редактировать невозможно.<br>Это действие нельзя отменить.</p>
            <div class="button-list">
                <button id="archive-btn" type="submit">В архив</button>
                <button id="archive-cancel-btn" type="reset">Отмена</button>
            </div>
        </form>
    </dialog>    
@endcan
@can('delete', $ticket)
    <dialog id="delete-dialog">
        <form method="POST" action="{{url()->current()}}" class="dialog-wrapper">
            @csrf
            @method('DELETE')
            <h2>Удалить тикет</h2>
            <p>Вы уверены, что хотите удалить данный тикет?<br>Это действие нельзя отменить.<br>Рекомендуется вместо этого перемещать закрытые тикеты в архив.</p>
            <div class="button-list">
                <button id="delete-btn" type="submit">Удалить</button>
                <button id="delete-cancel-btn" type="reset">Отмена</button>
            </div>
        </form>
    </dialog>    
@endcan
<script>
    $(document).ready(function() {
        $('#link-archive').on('click', function(e) {
            e.preventDefault();
            $('#archive-dialog')[0].showModal();
        });
        $('#link-delete').on('click', function(e) {
            e.preventDefault();
            $('#delete-dialog')[0].showModal();
        });
        $('#archive-cancel-btn').on('click', function(e) {
            e.preventDefault();
            $('#archive-dialog')[0].close();
        });
        $('#delete-cancel-btn').on('click', function(e) {
            e.preventDefault();
            $('#delete-dialog')[0].close();
        });
    });
</script>