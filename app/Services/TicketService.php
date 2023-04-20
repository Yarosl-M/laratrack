<?php

namespace App\Services;

use App\Models\Priority;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use RangeException;

class TicketService {
    public function __construct(private TicketActionService $actionService) {}
    
    // createTicket also contains the text of the first message and other info but only the user-supplied
    // info, not things like created_at and so on
    public function createTicket(array $createTicket): Ticket {
    }
    
    // again, the array is just makeshift dtos for now
    public function updateTicket(string $id, array $updateTicket): Ticket {
    }


    public function archiveTicket(string $id): Ticket {
        $ticket = Ticket::find($id);
        $timestamp = Carbon::now()->toDateTimeString();
        $ticket->update(['archived_at' => $timestamp]);
        return $ticket;
    }

    public function sendFeedback(int $rating) {
        if ($rating < 0 || $rating > 5) throw new RangeException('Rating should be in range [0, 5]');
    }
    
    // or maybe return an array of validation errors?
    public function validateTicket(Ticket $ticket) {
        if (isset($ticket->subject) && !ctype_space($ticket->subject)) {
            throw new ValidationException('Subject must not be empty');
        }
        // todo: more stuff?
    }
}