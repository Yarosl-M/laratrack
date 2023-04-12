<?php
namespace App\Enums;

enum UserType: string {
    case Client = 'client';
    case Operator = 'operator';
    case Admin = 'admin';
}