<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* A tech support ticket. */
class Ticket extends Model
{
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

    public function assignedTo($query, User $user) {
        return $query->where('assigned_to', $user->id);
    }
}
