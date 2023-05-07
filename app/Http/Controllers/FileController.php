<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// encapsulates working with files (e. g. to set up access permissions)
class FileController extends Controller {
    public function getTicketAttachment(string $ticketId, string $messageId, string $filename) {
        $t = Ticket::find($ticketId);
        if (isset($t) && Auth::user()->can('view', $t)) {
            $path = '/tickets/' . $ticketId . '/' . $messageId . '/' . $filename;
            abort_if(!Storage::disk('local')->exists($path),
            404, 'Файл не найден');
            return Storage::disk('local')->response($path);
        }
        abort(404);
    }

    // I could maybe also make it return the default one if there's none just so that nothing breaks down the road?
    public function getProfilePicture(string $userId, string $filename) {
        $u = User::find($userId);
        if (isset($u)) {
            $path = '/users/' . $userId . '/' . $filename;
        }
    }
}
