<?php

namespace App\Http\Controllers\Remote;

use App\Constants\Status;
use Illuminate\Http\Request;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\CashAccount;
use App\Models\EMoneyAccount;

class RemoteAccountController extends Controller
{
    use ApiResponse;

    public function addAmount(Request $request)
    {
        ['amount' => $amount] = $request->all();

        $cpt_amount = ($amount * 95) / 100;
        $profit = ($amount * 5) / 100;

        [$cash_cpt_acc, $cash_house_edge] = $this->getCashAccount();
        $cash_cpt_acc->update(['amount' => $cash_cpt_acc->amount + $cpt_amount]);
        $cash_house_edge->update(['amount' => $cash_house_edge->amount + $profit]);

        [$emoney_cpt_acc, $emoney_house_edge] = $this->getEmoneyAccount();
        $emoney_cpt_acc->update(['amount' => $emoney_cpt_acc->amount + $cpt_amount]);
        $emoney_house_edge->update(['amount' => $emoney_house_edge->amount + $profit]);

        return $this->responseSucceed(
            message: "Successfully add amount to accounts!."
        );
    }

    public function subtractAmount(Request $request)
    {
        ['amount' => $amount] = $request->all();

        [$cash_cpt_acc, $cash_house_edge] = $this->getCashAccount();
        $cash_cpt_acc->update(['amount' => $cash_cpt_acc->amount - $amount]);

        [$emoney_cpt_acc, $emoney_house_edge] = $this->getEmoneyAccount();
        $emoney_cpt_acc->update(['amount' => $emoney_cpt_acc->amount - $amount]);

        return $this->responseSucceed(
            message: "Successfully subtract amount from account!."
        );
    }

    private function getCashAccount()
    {
        $cash_accounts = CashAccount::whereIn('reference_id', [Status::CAPITAL_ACC, Status::HOUSE_EDGE])->get();

        $cash_cpt_acc = $cash_accounts->where('reference_id', Status::CAPITAL_ACC)->first();
        $cash_house_edge = $cash_accounts->where('reference_id', Status::HOUSE_EDGE)->first();

        return [$cash_cpt_acc, $cash_house_edge];
    }

    private function getEmoneyAccount()
    {
        $emoney_accounts = EMoneyAccount::whereIn('reference_id', [Status::CAPITAL_ACC, Status::HOUSE_EDGE])->get();

        $emoney_cpt_acc = $emoney_accounts->where('reference_id', Status::CAPITAL_ACC)->first();
        $emoney_house_edge = $emoney_accounts->where('reference_id', Status::HOUSE_EDGE)->first();

        return [$emoney_cpt_acc, $emoney_house_edge];
    }
}
