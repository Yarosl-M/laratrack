<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/* Tag of ticket */
class Tag extends Model
{
    protected $fillable = ['name'];
    public $incrementing = false;
    use HasUlids;
    use HasFactory;
    public $timestamps = false;
    public function tickets(): BelongsToMany {
        return $this->belongsToMany(Ticket::class);
    }
}
