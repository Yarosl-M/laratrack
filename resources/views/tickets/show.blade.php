{{-- Show a single ticket page --}}
@php
    use App\Models\User;
    use App\Models\Message;
    use App\Models\Ticket;
    use App\Models\ThreadAction;
@endphp
@props(['ticket', 'entries'])
<x-layout :stylesheets="$sheets" :title="$title">
    <script src="https://unpkg.com/javascript-time-ago@2.5.9/bundle/javascript-time-ago.js"></script>
    <h1>
        {{$ticket->subject}}
    </h1>
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
    <x-message-form :ticket="$ticket"/>
</x-layout>