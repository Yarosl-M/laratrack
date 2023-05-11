<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* A tech support ticket. */
class Ticket extends Model
{
    protected $fillable = ['client_id', 'assigned_to', 'subject', 'priority_id', 'is_open', 'client_rating', 'archived_at'];
    public $incrementing = false;
    use HasUlids;
    use HasFactory;

    public function scopeActive($query) {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived($query) {
        return $query->whereNotNull('archived_at');
    }

    public function scopeOpen($query) {
        return $query->where('is_open', 'true');
    }

    public function scopeClosed($query) {
        return $query->where('is_open', 'false');
    }

    public function scopeFilter($query, array $filters) {
        if ($filters['search'] ?? false) {
            return $query->where('subject', 'ilike', '%' . $filters['search'] . '%');
        }
    }

    public function assignedTo($query, User $user) {
        return $query->where('assigned_to', $user->id);
    }

    public function thread_entries(): HasMany {
        return $this->hasMany(ThreadEntry::class, 'ticket_id');
    }
    public function messages(): HasMany {
        return $this->hasMany(Message::class, 'ticket_id');
    }
    public function thread_actions(): HasMany {
        return $this->hasMany(ThreadAction::class, 'ticket_id');
    }
    public function client(): BelongsTo {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function assignee(): BelongsTo {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function priority(): BelongsTo {
        return $this->belongsTo(Priority::class, 'priority_id');
    }
    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class);
    }
}
