@can('view_open', App\Models\Ticket::class)
    <a class="navlink" href="/tickets">Тикеты</a>
@endcan
@can('view_archive', App\Models\Ticket::class)
    <a class="navlink" href="/tickets/archive">Архив</a>
@endcan
@can('view_open', App\Models\Ticket::class)
    {{-- <a href="/tickets" class="nav-highlight">Мои тикеты</a> --}}
@endcan