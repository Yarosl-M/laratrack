<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Tag of ticket */
class Tag extends Model
{
    use HasUlids;
    use HasFactory;
    public $timestamps = false;
}
