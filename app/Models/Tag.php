<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Tag of ticket */
class Tag extends Model
{
    use HasFactory;
    protected $table = 'tag';
    public $timestamps = false;
}
