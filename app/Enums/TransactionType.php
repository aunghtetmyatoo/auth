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

    // Cash Transaction
    case ITToITHead = 'IT to IT Head Transaction';
    case ITHeadToSuperAdmin = 'IT Head to Super Admin Transaction';
    case SuperAdminToFinanceManager = 'Super Admin to Finance Manager Transaction';
    case FinanceManagerToSuperAdmin = 'Finance Manager to Super Admin Transaction';
    case FinanceManagerToFinance = 'Finance Manager to Finance Transaction';
    case FinanceToOperationManager = 'Finance to Operation Manager';
    case Admin = 'Admin Transaction';
}
