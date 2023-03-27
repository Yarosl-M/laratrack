<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* User permission for access control */
class Permission extends Model
{
    use HasFactory;
    protected $table = 'permission';
    public $timestamps = false;
}
