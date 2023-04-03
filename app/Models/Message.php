<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Client and operator messages in the ticket */
class Message extends Model
{
    use HasUlids;
    use HasFactory;
}
