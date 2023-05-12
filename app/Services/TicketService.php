<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Priority;
use App\Models\Tag;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use RangeException;

class TicketService {
    public function __construct(private TicketActionService $actionService) {}
    
    // createTicket also contains the text of the first message and other info but only the user-supplied
    // info, not things like created_at and so on (basically just a makeshift dto)
    public function createTicket(array $createTicket): Ticket {
        $ticketAttr = [
            'subject' => $createTicket['subject'],
            'client_id' => $createTicket['client_id']
        ];
        $firstMessageAttr = [
            'user_id' => $createTicket['client_id'],
            'content' => $createTicket['msg_text'],
            'attachments' => json_encode( $createTicket['msg_attachments']),
        ];
        $ticket = Ticket::create($ticketAttr);
        $firstMessageAttr['ticket_id'] = $ticket->id;
        $firstMsg = Message::create($firstMessageAttr);
        return $ticket;
    }
    
    // again, the array is just makeshift dtos for now
    // also add user here as a parameter instead
    // add method to user model which will check if it has superuser or otherwise a provided role
    // ok it's apparently instead done with gates
    public function updateTicket(string $id, array $updateTicket): Ticket {
        $ticket = Ticket::find($id);
        $user = Auth::user();
        if (array_key_exists('assigned_to', $updateTicket)) {
            // unassign ticket
            if ($updateTicket['assigned_to'] == null) {
                $last = $ticket->assignee;
                $this->actionService->unassignTicket($ticket, $user, $last);
                $ticket->assigned_to = null;
            }
            // assign or reassign ticket
            else {
                $assignee = User::find($updateTicket['assigned_to']);
                $this->actionService->assignTicket($ticket, $user, $assignee);
                $ticket->assigned_to = $assignee->id;
            }
        }
        // change priority
        if (array_key_exists('priority_id', $updateTicket)) {
            $old = $ticket->priority;
            $new = Priority::find($updateTicket['priority_id']);
            $this->actionService->changePriority($ticket, $user, $old, $new);
            $ticket->priority_id = $new->id;
        }
        if (array_key_exists('is_open', $updateTicket)) {
         // close ticket
            if ($updateTicket['is_open'] == false) {
                $this->actionService->closeTicket($ticket, $user);
                $ticket->is_open = false;
            }
            // reopen
            else {
                $this->actionService->reopenTicket($ticket, $user);
                $ticket->is_open = true;
            }
        }

        if (array_key_exists('tags', $updateTicket)) {
            $oldTags = $ticket->tags;
            $newTags = [];
            foreach ($updateTicket['tags'] as $tagId) {
                $tag = Tag::find($tagId);
                $newTags[] = $tag;
            }
            $oldIds = [];
            foreach ($oldTags as $tag) {
                $oldIds[] = $tag->id;
            }
            $newIds = [];
            foreach ($newTags as $tag) {
                $newIds[] = $tag->id;
            }

            $this->actionService->changeTags($ticket, $user, $oldIds, $newIds);

            $ticket->tags()->sync($newIds);
        }
        // archive it and send client rating in a separate method
        $ticket->save();
        return $ticket;
    }

    public function addMessage(array $messageAttr): Message {
        // ticket id (str)
        // also user id (str)
        // content (str)
        // files (arr of str)
        $m = new Message;
        $m->ticket_id = $messageAttr['ticket_id'];
        $m->user_id = $messageAttr['user_id'];
        $m->content = $messageAttr['content'];
        $files_json = json_encode($messageAttr['files']);
        $m->attachments = $files_json;
        $m->save();
        return $m;
    }

    public function archiveTicket(string $id): Ticket {
        $ticket = Ticket::find($id);
        $user = Auth::user();
        $action = $this->actionService->archiveTicket($ticket, $user);
        $timestamp = $action->created_at;
        $timestamp = Carbon::now()->toDateTimeString();
        
        $ticket->update(['archived_at' => $timestamp]);
        return $ticket;
    }

    public function sendFeedback(string $id, User $user, int $rating) {
        if ($rating < 0 || $rating > 5) throw new RangeException('Rating should be in range [0, 5]');
        $ticket = Ticket::find($id);
        $this->actionService->sendFeedback($ticket, $user, $rating);

        $ticket->client_rating = $rating;
        $ticket->save();
        return $ticket;
    }
    
    // or maybe return an array of validation errors?
    public function validateTicket(Ticket $ticket) {
        if (isset($ticket->subject) && !ctype_space($ticket->subject)) {
            throw new ValidationException('Subject must not be empty');
        }
        // todo: more stuff?
    }
}