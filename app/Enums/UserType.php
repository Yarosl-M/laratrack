<?php

enum UserType: string {
    case Client = 'client';
    case Operator = 'operator';
    case Admin = 'admin';
}