<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/* A user of the system (including clients and operators and admins all together) */
class User extends Authenticatable
{
    use HasUlids;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeClients($query) {
        return $query->where('type', UserType::Client->value);
    }

    public function scopeEmployees($query) {
        return $query->where('type', UserType::Operator->value)
        ->orWhere('type', UserType::Admin->value);
    }

    public function messages(): HasMany {
        return $this->hasMany(Message::class, 'user_id');
    }
    public function permissions(): BelongsToMany {
        return $this->belongsToMany(Permission::class);
    }
    public function thread_actions(): HasMany {
        return $this->hasMany(ThreadAction::class, 'user_id');
    }
    public function thread_entries(): HasMany {
        return $this->hasMany(ThreadEntry::class, 'user_id');
    }
    public function assigned_tickets(): HasMany {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }
    public function created_tickets(): HasMany {
        return $this->hasMany(Ticket::class, 'client_id');
    }
}
