<?php

namespace App\Models;

use App\Enums\ActionType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* Actions in ticket (e. g. tag updates, closing/reopening, assigning ticket to an operator etc.) */
class ThreadAction extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'attributes'];
    public $incrementing = false;
    use HasUlids;
    use HasFactory;

    public function scopeOfType($query, ActionType $type) {
        return $query->where('attributes->type', $type->value);
    }

    public function ticket(): BelongsTo {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
