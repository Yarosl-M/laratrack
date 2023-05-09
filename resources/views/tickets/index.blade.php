<x-layout :stylesheets="['style_index']" :title="'Просмотр тикетов'">
    @include('partials._search')
    <div class="ticket-list">
        @foreach ($tickets as $ticket)
            <x-ticket-card :ticket="$ticket"/>
        @endforeach
    </div>
</x-layout>