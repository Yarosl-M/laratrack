<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketRequest;
use App\Models\Message;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketServiee) {}

    public function index(Request $request) {
        return view('tickets.index', 
    ['tickets' => Ticket::latest()->paginate(10)]);
    }

    public function create() {
        return view('tickets.create', ['sheets' => ['style_form', 'test'], 'title' => 'Создать тикет']);
    }

    public function store(CreateTicketRequest $request) {
        /**
         * subject
         * client_id
         * user_id
         * content
         * attachments
         */
        $ticketAttr = $request->safe()->only('subject');
        $ticketAttr['client_id'] = $request->user()->id;
        $ticket = new Ticket();
        $ticket->subject = $ticketAttr['subject'];
        $ticket->client_id = $ticketAttr['client_id'];
        $ticket->save();

        $firstMessageAttr = $request->safe()->only('content');
        $firstMessageAttr['user_id'] = $ticketAttr['client_id'];
        $firstMessageAttr['ticket_id'] = $ticket->id;
        $message = new Message();
        $message->user_id = $firstMessageAttr['user_id'];
        $message->ticket_id = $firstMessageAttr['ticket_id'];
        $message->content = $firstMessageAttr['content'];
        $message->save();

        if ($request->hasFile('attachments')) {
            $files = $request->file('attachments');
            $attachments = [];
            foreach ($files as $file) {
                $attachments[] = $file->getClientOriginalName();
                $path = $file->storeAs('/public/tickets/' . $ticket->id . '/' . $message->id,
                $file->getClientOriginalName());
            }
            $message->attachments = json_encode($attachments);
            $message->save();
        }

        return redirect('/tickets/' . $ticket->id);
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
