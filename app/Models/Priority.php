<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Ticket priority */
class Priority extends Model
{
    use HasUlids;
    use HasFactory;
    protected $table = 'priorities';
    public $timestamps = false;
}
