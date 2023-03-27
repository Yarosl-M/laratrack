<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* Pivot table between Permission and User (many-to-many relationship) */
class PermissionUser extends Model
{
    use HasFactory;
    protected $table = 'permission_user';
    public $timestamps = false;
}
