<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/* User permission for access control */
class Permission extends Model
{
    use HasUlids;
    use HasFactory;
    public $timestamps = false;

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class);
    }
}
