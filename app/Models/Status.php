<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Status of the ticket
TODO: MOVE STATUS (E. G. CLOSE REASON) TO TAGS, ADD CLOSED/OPENED COLUMN ON TICKER */
class Status extends Model
{
    use HasFactory;
    protected $table = 'status';
    public $timestamps = false;
}
