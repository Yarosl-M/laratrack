<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketRequest;
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
}
