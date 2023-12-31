<?php

namespace App\Enums;

enum TransactionType: string
{
    case Gift = 'Gift Transaction';
    case Cash = 'Cash Transaction';
    case Bot = 'Bot Transaction';
    case Player = 'Player Transaction';
    case Withdraw = 'Withdraw Request Transaction';
    case Recharge = 'Recharge Request Transaction';
    case AmountToCoin = 'Amount To Coin Transaction';
    case CoinToAmount = 'Coin To Amount Transaction';

        // Cash Transaction
    case ITToITHead = 'IT to IT Head Transaction';
    case ITHeadToSuperAdmin = 'IT Head to Super Admin Transaction';
    case SuperAdminToFinanceManager = 'Super Admin to Finance Manager Transaction';
    case FinanceManagerToFinance = 'Finance Manager to Finance Transaction';
    case FinanceToOperationManager = 'Finance to Operation Manager';
    case Admin = 'Admin Transaction';
    case FinanceToFinanceManager = 'Finance to Finance Manager Transaction';
    case FinanceManagerToSuperAdmin = 'Finance Manager to Super Admin Transaction';
    case OperationManagerToPlayer = 'Operation Manager To Player';
    case OperationManagerToBot = 'Operation Manager To Bot';
    case OperationManagerToFinance = 'Operation Manager To Finance';
    case DeactivatedUserByOperationManager = 'Deactivated User By Operation Manager';
}
