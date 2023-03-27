<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Pivot table between Tag and Ticket (many-to-many relationship) */
class TagTicket extends Model
{
    use HasFactory;
    protected $table = 'tag_ticket';
    public $timestamps = false;
}
