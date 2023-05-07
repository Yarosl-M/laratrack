<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketRequest;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketServiee) {}

    public function create() {
        return view('tickets.create', ['sheets' => ['style_form', 'test'], 'title' => 'Создать тикет']);
    }

    public function store(CreateTicketRequest $request) {
        
    }

    public function show(Ticket $ticket) {
        $entries = $ticket->messages->concat($ticket->thread_actions)->sortBy('created_at');
        $titleSubject = (strlen($ticket->subject) < 30) ? $ticket->subject :
        (substr($ticket->subject, 0, 25) . '…');
        return view('tickets.show', [
            'ticket' => $ticket,
            'entries' => $entries,
            'sheets' => ['style_ticket'],
            'title' => $titleSubject,
        ]);
    }
}
