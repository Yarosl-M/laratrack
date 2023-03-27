<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Ticket priority */
class Priority extends Model
{
    use HasFactory;
    protected $table = 'priority';
    public $timestamps = false;
}
