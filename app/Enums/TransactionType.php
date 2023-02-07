<?php

namespace App\Enums;

enum TransactionType: string
{
    case Gift = 'Gift Transaction';
    case Cash = 'Cash Transaction';
    case Bot = 'Bot Transaction';
    case Player = 'Player Transaction';
    case Withdraw='Withdraw Request Transaction';
    case Recharge='Recharge Request Transaction';
}
