<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* A tech support ticket. */
class Ticket extends Model
{
    use HasUlids;
    use HasFactory;
}
