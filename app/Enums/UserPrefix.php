<?php

namespace App\Enums;

enum UserPrefix: string
{
    case Player = 'PLY';
    case Admin = 'ADM';
    case Bot = 'BT';
}
