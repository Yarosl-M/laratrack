<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\ModelsPruned;

/* Base table for thread actions and messages */
class ThreadEntry extends ModelsPruned
{
    protected $fillable = ['ticket_id', 'user_id'];
    public $incrementing = false;
    use HasUlids;
    use HasFactory;
    protected $table = 'thread_entries';
}
