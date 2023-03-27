<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Actions in ticket (e. g. tag updates, closing/reopening, assigning ticket to an operator etc.) */
class ThreadAction extends Model
{
    use HasFactory;
    protected $table = 'thread_action';
}
