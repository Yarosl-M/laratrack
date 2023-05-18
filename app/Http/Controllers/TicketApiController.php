<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMessageRequest;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class TicketApiController extends Controller {
    public function __construct(private TicketService $ticketService) { }
    // добавить сообщение в тикет
    public function comment(AddMessageRequest $request, Ticket $ticket) {
        $this->authorize('send_message', $ticket);
        $filenames = [];

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $attachments = [];
            foreach ($files as $file) {
                $filenames[] = $file->getClientOriginalName();
            }
        }
        $m = $this->ticketService->addMessage(
            [
                'ticket_id' => $ticket->id,
                'user_id' => $request->user()->id,
                // 'user_id' => User::first()->id,
                'content' => ($request->safe()->only('content'))['content'],
                'files' => $filenames,
            ]);
        if ($request->hasFile('files')) {
            foreach ($files as $file) {
                $path = $file->storeAs('/public/tickets/' . $ticket->id . '/' . $m->id,
                $file->getClientOriginalName());    
            }
        }
        $component = view('components.ticket-message', [
            'ticket' => $m->ticket()->first(),
            'message' => $m,
        ]);
        $html = $component->render();
        return response()->json([
            'html' => $html,
        ]);
    }
}