<?php
namespace App\Enums;

enum ActionType: string {
    case TagsChanged = 'tags_changed';
    case PriorityChanged = 'priority_changed';
    case TicketAssigned = 'ticket_assigned';
    case TicketUnassigned = 'ticket_unassigned';
    case TicketClosed = 'ticket_closed';
    case TicketReopened = 'ticket_reopened';
    case FeedbackSent = 'feedback_sent';
    case TicketArchived = 'ticket_archived';
}