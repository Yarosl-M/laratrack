<?php

namespace App\Services;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Priority;
use App\Models\ThreadAction;
use App\Enums\ActionType;

class TicketActionService {
    // Инкапсулирует работу с объектами thread_action для удобства работы
    public function changeTags(Ticket $ticket, User $user, array $old_tags, array $new_tags): ThreadAction {
        $action = [
            'old_tags' => $old_tags,
            'new_tags' => $new_tags,
        ];
        return $this->createAction(ActionType::TagsChanged, $ticket, $user, $action);
    }

    public function changePriority(Ticket $ticket, User $user, Priority $old, Priority $new): ThreadAction {
        $action = [
            'old_priority' => $old->id,
            'new_priority' => $new->id,
        ];
        return $this->createAction(ActionType::PriorityChanged, $ticket, $user, $action);
    }

    public function assignTicket(Ticket $ticket, User $user, User $assignee) {
        $action = ['assignee' => $assignee->id];
        return $this->createAction(ActionType::TicketAssigned, $ticket, $user, $action);
    }

    public function unassignTicket(Ticket $ticket, User $user, User $last) {
        $action = ['last_assignee' => $last->id];
        return $this->createAction(ActionType::TicketUnassigned, $ticket, $user, $action);
    }

    public function closeTicket(Ticket $ticket, User $user) {
        // no parameters required
        return $this->createAction(ActionType::TicketClosed, $ticket, $user, []);
    }

    public function reopenTicket(Ticket $ticket, User $user) {
        // no parameters required too
        return $this->createAction(ActionType::TicketReopened, $ticket, $user, []);
    }

    public function sendFeedback(Ticket $ticket, User $user, int $rating) {
        $action = ['rating' => $rating];
        return $this->createAction(ActionType::FeedbackSent, $ticket, $user, $action);
    }

    public function archiveTicket(Ticket $ticket, User $user) {
        // no parameters either I suppose? might have the timestamp later but keep it like that for now
        return $this->createAction(ActionType::TicketArchived, $ticket, $user, []);
    }

    private function createAction(ActionType $type, Ticket $ticket, User $user, array $action_params): ThreadAction {
        $attr_json = json_encode($action_params);
        $fields = [
            'type' => $type->value,
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'attributes' => $attr_json,
        ];
        return ThreadAction::create($fields);
    }
}