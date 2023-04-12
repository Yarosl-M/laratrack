<?php

namespace App\Services;

class TicketService {
    public function __construct(private TicketActionService $actionService) {}
}