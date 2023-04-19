<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* Actions in ticket (e. g. tag updates, closing/reopening, assigning ticket to an operator etc.) */
class ThreadAction extends Model
{
    public $incrementing = false;
    use HasUlids;
    use HasFactory;
    public function ticket(): BelongsTo {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
