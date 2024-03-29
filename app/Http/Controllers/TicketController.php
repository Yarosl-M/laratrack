<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Message;
use App\Models\Priority;
use Illuminate\Http\Request;
use App\Services\TicketService;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use App\Http\Requests\AddMessageRequest;
use App\Http\Requests\CreateTicketRequest;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService) {}

    public function main() {
        return view('main', ['sheets' => ['style_welcome'], 'title' => 'Добро пожаловать']);
    }

    private function prepareIndexView(Request $request, bool $active) {
        $sortBy = $request->query('sort_by', 'latest');
        $search = $request->query('search', null);
        $isOpen = $request->query('status', 'all');
        $tagIds = $request->query('tags', null);

        // should return a paginator still
        $tickets = $this->ticketService->getAll($active, $sortBy, $search,
            $isOpen, $tagIds, $request->user());

        return view('tickets.index', [
            'tickets' => $tickets, 'title' => 'Просмотр тикетов', 'tags' => Tag::get(),
            'queryData' => [
                'search' => $search,
                'sort_by' => $sortBy,
                'status' => $isOpen,
                'tags' => $tagIds
            ]
        ]);

    }

    public function index(Request $request) {
        $this->authorize('view_open', Ticket::class);
        return $this->prepareIndexView($request, true); }

    public function archive(Request $request) {
        $this->authorize('view_archive', Ticket::class);
        return $this->prepareIndexView($request, false); }

    public function create() {
        $this->authorize('create', Ticket::class);
        return view('tickets.create', ['sheets' => ['style_form', 'test'], 'title' => 'Создать тикет']);
    }

    public function settings(Request $request, Ticket $ticket) {
        $this->authorize('change_params', $ticket);
        $titleSubject = (strlen($ticket->subject) < 25) ? $ticket->subject :
        (substr($ticket->subject, 0, 20) . '…');
        return view('tickets.settings', ['ticket' => $ticket, 'sheets' => ['style_ticket_settings'],
        'title' => $titleSubject]);
    }

    public function destroy(Request $request, Ticket $ticket) {
        $this->authorize('delete', $ticket);
        $archive = $ticket->archived_at != null;
        $ticket->delete();
        return redirect($archive ? '/tickets/archive' : '/tickets');
    }

    public function close(Request $request, Ticket $ticket) {
        $this->authorize('change_status', $ticket);
        $this->ticketService->close($ticket, $request->user());
        return back();
    }

    public function reopen(Request $request, Ticket $ticket) {
        $this->authorize('change_status', $ticket);
        $this->ticketService->open($ticket, $request->user());
        return back();
    }

    public function move_to_archive(Request $request, Ticket $ticket) {
        $this->authorize('archive', $ticket);
        $this->ticketService->archiveTicket($ticket->id);
        return back();
    }

    public function store(CreateTicketRequest $request) {
        $this->authorize('create', Ticket::class);
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
        $ticket->priority_id = Priority::where('name', 'Не установлен')->first()->id;
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

    public function update(Request $request, Ticket $ticket) {
        $this->authorize('change_params', $ticket);
        $keys = array_keys($request->all());
        $updateTicket = [];

        $reqTags = collect(array_filter($keys, fn($k) => str_starts_with($k, 'tag-')))
        ->map(fn($t) => substr($t, 4))->toArray();
        $ticketTags = $ticket->tags()->get()->pluck('id')->toArray();
        // check if any tags were actually modified
        if (count($reqTags) !== count($ticketTags) || count(array_intersect($reqTags, $ticketTags)) !== count($reqTags)) {
            $updateTicket['tags'] = $reqTags;
        }

        $pid = substr($request->input('priority'), 2);
        if ($pid !== $ticket->priority_id) {
            $updateTicket['priority_id'] = $pid;
        }

        // old null, new null => no action
        // old null, new not null => assign
        // old not null, new null => unassign
        // old not null, new not null => check, assign if different
        $aid = $request->input('assignee');
        if ($aid != $ticket->assigned_to) {
            // this if-block is skipping cases 1 and 4-same
            // and actually since we can pass null to the service, we skip everything else too
            $updateTicket['assigned_to'] = $aid;
        }

        $this->ticketService->updateTicket($ticket->id, $updateTicket);

        return redirect('/tickets/' . $ticket->id); 
    }

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

        return redirect('/tickets/'.$ticket->id.'#'.$m->id);
    }

    public function show(Ticket $ticket) {
        $this->authorize('view', $ticket);
        $actions = $ticket->thread_actions;
        $entries = $ticket->messages->concat($actions)->sortBy('created_at');
        $titleSubject = (strlen($ticket->subject) < 25) ? $ticket->subject :
        (substr($ticket->subject, 0, 20) . '…');
        return view('tickets.show', [
            'ticket' => $ticket,
            'entries' => $entries,
            'sheets' => ['style_ticket', 'style_sidebar'],
            'title' => $titleSubject,
        ]);
    }

    public function rate(Request $request, Ticket $ticket) {
        if ($request->user()->id !== $ticket->client_id
        || $ticket->client_rating !== null || !$ticket->isRateable()) return back();
        $fields = $request->validate([
            'rating' => 'integer|numeric|between:1,5'
        ]);
        $rating = intval($fields['rating']);
        $this->ticketService->sendFeedback($ticket->id, $request->user(), $rating);
        return back();
    }
}
