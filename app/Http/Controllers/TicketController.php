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
        $actions = $ticket->thread_actions;
        $entries = $ticket->messages->concat($actions)->sortBy('created_at');
        $titleSubject = (strlen($ticket->subject) < 25) ? $ticket->subject :
        (substr($ticket->subject, 0, 20) . '…');
        return view('tickets.show', [
            'ticket' => $ticket,
            'entries' => $entries,
            'sheets' => ['style_ticket'],
            'title' => $titleSubject,
        ]);
    }
}
