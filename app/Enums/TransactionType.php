<?php

namespace App\Enums;

enum TransactionType: string
{
    case Gift = 'Gift Transaction';
    case Cash = 'Cash Transaction';
    case Bot = 'Bot Transaction';
    case Player = 'Player Transaction';
}
