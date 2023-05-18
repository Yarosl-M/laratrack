<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any tickets.
     */
    public function view_any(User $user): bool {
        return $user->hasPermission('view_all_tickets') && $user->hasPermission('view_archived_tickets');
    }

    public function view_open(User $user): bool {
        return $user->hasPermission('view_assigned_tickets')
        || $user->hasPermission('view_unassigned_tickets')
        || $this->view_any($user);
    }
    
    public function view_archive(User $user): bool {
        return $user->hasPermission('view_archived_tickets')
        || $this->view_any($user);
    }

    /**
     * Determine whether the user can view the ticket.
     */
    public function view(User $user, Ticket $ticket): bool {
        if ($ticket->client_id === $user->id) return $user->hasPermission('view_tickets');
        if ($ticket->archived_at !== null) return $this->view_archive($user);
        return ($user->hasPermission('view_all_tickets')) ||
        ($user->hasPermission('view_assigned_tickets') && $ticket->assigned_to === $user->id) ||
        ($user->hasPermission('view_unassigned_tickets') && $ticket->assigned_to == null);
    }

    /**
     * Determine whether the user can create tickets.
     */
    public function create(User $user): bool {
        return $user->hasPermission('create_tickets');
    }

    /**
     * Determine whether the user can send a message in the ticket.
     */
    public function send_message(User $user, Ticket $ticket): bool {
        if ($ticket->archived_at !== null) return false;
        return $this->view($user, $ticket) &&
        ($user->hasPermission('send_messages_client') && $ticket->client_id === $user->id ||
        $user->hasPermission('send_messages_operator'));
    }
    
    /**
     * Determine whether the client can send feedback to the ticket.
     * This one doesn't calculate when it becomes available, only whether a user is authorized to do that
     * or not.
     */
    public function send_feedback(User $user, Ticket $ticket): bool {
        if ($ticket->archived_at !== null) return false;
        return $user->hasPermission('view_tickets') && $ticket->client_id === $user->id &&
        $user->hasPermission('send_feedback');
    }

    /**
     * Determine whether the user can change ticket parameters (currently - tags and priority).
     */
    public function change_params(User $user, Ticket $ticket): bool {
        if ($ticket->archived_at !== null) return false;
        return $this->view($user, $ticket) && $user->hasPermission('change_ticket_params');
    }

    /**
     * Determine whether the user can close and reopen the ticket.
     */
    public function change_status(User $user, Ticket $ticket): bool {
        if ($ticket->archived_at !== null) return false;
        return $this->view($user, $ticket) && $user->hasPermission('change_ticket_status');
    }

    /**
     * Determine whether the user can assign a ticket to another user.
     * Again, no checks are made whether it's a valid operation (e. g. assigning or unassigning yourself)
     */
    public function assign(User $user, Ticket $ticket): bool {
        return $this->view($user, $ticket) && $user->hasPermission('assign_tickets');
    }

    /**
     * Determine whether the user can archive the ticket.
     */
    public function archive(User $user, Ticket $ticket): bool {
        return $this->view($user, $ticket) &&
        $user->hasPermission('archive_tickets');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool {
        return $this->view($user, $ticket) &&
        $user->hasPermission('delete_tickets');
    }
}
