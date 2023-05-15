<x-layout :stylesheets="['style_index', 'style_pagination']" :title="'Архив'">
    @include('partials._search')
    <div class="ticket-list">
        @foreach ($tickets as $ticket)
            <x-ticket-card :ticket="$ticket"/>
        @endforeach
    </div>
    <div class="pagination-wrapper">
        {{$tickets->links()}}
    </div>
</x-layout>