<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Base table for thread actions and messages */
class ThreadEntry extends Model
// why would I even need that
{
    use HasUlids;
    use HasFactory;
    protected $table = 'thread_entries';
}
