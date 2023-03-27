<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Base table for thread actions and messages */
class ThreadEntry extends Model
{
    // gotta figure out the Postgres x Laravel magic some time later
    use HasFactory;
    protected $table = 'thread_entry';
}
