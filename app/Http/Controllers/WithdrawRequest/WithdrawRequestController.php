<?php

namespace App\Http\Controllers\WithdrawRequest;

use Exception;
use App\Models\User;
use App\Models\Admin;
use App\Models\History;
use App\Constants\Status;
use App\Actions\StoreFile;
use Illuminate\Support\Str;
use App\Constants\ServerPath;
use App\Actions\Endpoint;
use App\Models\RechargeChannel;
use App\Models\TransactionType;
use App\Models\WithdrawChannel;
use App\Models\WithdrawRequest;
use App\Constants\ChannelPrefix;
use App\Services\Crypto\DataKey;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Actions\Auth\CheckPasscode;
use App\Constants\TelegramConstant;
use App\Models\WithdrawTransaction;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Actions\Transaction\ReferenceId;
use App\CustomFunctions\ResponseHelpers;
use App\Actions\Transaction\LogTransaction;
use App\Actions\RechargeGenerateReferenceId;
use App\Actions\RechargeWithdrawReferenceId;
use App\Actions\Transaction\MonitorTransaction;
use App\Constants\ErrorLogStatus;
use App\Exceptions\WithdrawAmountNotEnoughExcepion;
use App\Enums\TransactionType as EnumsTransactionType;
use App\Http\Requests\Api\WithdrawRequest\KbzCreateRequest;
use App\Http\Requests\Api\WithdrawRequest\AlipayCreateRequest;
use App\Http\Requests\Api\WithdrawRequest\WeChatCreaterequest;
use App\Http\Requests\Api\WithdrawRequest\BankCardCreateRequest;
use App\Http\Requests\Api\WithdrawRequest\Enquiry\AliPayRequest;
use App\Http\Requests\Api\WithdrawRequest\Enquiry\KbzPayRequest;
use App\Http\Requests\Api\WithdrawRequest\Enquiry\WeChatRequest;
use App\Http\Requests\Api\WithdrawRequest\ThaibahtCreaterequest;
use App\Http\Requests\Api\WithdrawRequest\Enquiry\BankCardRequest;
use App\Http\Requests\Api\WithdrawRequest\Enquiry\ThaiBahtRequest;
use App\Http\Resources\Api\WithdrawRequest\WithdrawRequestResource;
use App\Http\Resources\Api\RechargeChannel\RechargeChannelCollection;
use App\Http\Resources\Api\WithdrawChannel\WithdrawChannelCollection;
use App\Http\Resources\Api\WithdrawRequest\WithdrawRequestCollection;
use App\Models\CashAccount;
use App\Models\MonitorLog;
use Carbon\Carbon;

class WithdrawRequestController extends Controller
{
    use ApiResponse;

    public function __construct(private Endpoint $endpoint)
    {
    }


    public function channels()
    {

        $channels = WithdrawChannel::all();
        return $this->responseSucceed([
            'channels' => new WithdrawChannelCollection($channels),
        ]);
    }

    public function findWithdrawChannel(string $channel_name)
    {

        $channel = WithdrawChannel::where('name', $channel_name)->first();
        if (!$channel) {
            return [
                'result' => 0,
                'message' => $channel_name . ' is not found.'
            ];
        }
        return [
            'result' => 1,
            'message' => 'Success',
            'data' => $channel
        ];
    }

    public function enquiryKbz(KbzPayRequest $request)
    {
        $findWithdrawChannel = $this->findWithdrawChannel('KBZ Pay');

        $channel = $findWithdrawChannel['data'];
        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }

        return $this->responseSucceed([
            'withdraw_amount' => "{$request->amount} MMK",
            'handling_fee' => "{$channel->handling_fee} MMK",
            'balance' => auth()->user()->amount . "MMK",
            // 'exchange_rate' => "¥ 1 = {$channel->exchange_currency->sign} {$channel->exchange_currency->buy_rate}",
            'actual_arrival' => "{$request->amount} MMK",
        ]);
    }

    public function enquiryWechat(WeChatRequest $request)
    {
        $findWithdrawChannel = $this->findWithdrawChannel('We Chat');
        $channel = $findWithdrawChannel['data'];
        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->responseSucceed([
            'withdraw_amount' => "{$request->amount} MMK",
            'handling_fee' => "{$channel->handling_fee} MMK",
            'balance' => auth()->user()->amount . "MMK",
            'exchange_rate' => "1 MMK = {$channel->exchange_currency->sign} {$channel->exchange_currency->buy_rate}",
            'actual_arrival' => $channel->exchange_currency->sign . " " . $request->amount * $channel->exchange_currency->buy_rate,
        ]);
    }

    public function enquiryAliPay(AliPayRequest $request)
    {
        $findWithdrawChannel = $this->findWithdrawChannel('Alipay');
        $channel = $findWithdrawChannel['data'];
        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->responseSucceed([
            'withdraw_amount' => "{$request->amount} MMK",
            'handling_fee' => "{$channel->handling_fee} MMK",
            'balance' => auth()->user()->amount . "MMK",
            'exchange_rate' => "1 MMK = {$channel->exchange_currency->sign} {$channel->exchange_currency->buy_rate}",
            'actual_arrival' => $channel->exchange_currency->sign . " " . $request->amount * $channel->exchange_currency->buy_rate,
        ]);
    }

    public function enquiryThaiBaht(ThaiBahtRequest $request)
    {
        $findWithdrawChannel = $this->findWithdrawChannel('Thai Baht');
        $channel = $findWithdrawChannel['data'];
        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->responseSucceed([
            'withdraw_amount' => "{$request->amount} MMK",
            'handling_fee' => "{$channel->handling_fee} MMK",
            'balance' => auth()->user()->amount . "MMK",
            'exchange_rate' => "1 MMK = {$channel->exchange_currency->sign} {$channel->exchange_currency->buy_rate}",
            'actual_arrival' => $channel->exchange_currency->sign . " " . $request->amount * $channel->exchange_currency->buy_rate,
        ]);
    }

    public function enquiryBankCard(BankCardRequest $request)
    {

        $findWithdrawChannel = $this->findWithdrawChannel('Bank Card');
        $channel = $findWithdrawChannel['data'];
        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->responseSucceed([
            'withdraw_amount' => "{$request->amount} MMK",
            'handling_fee' => "{$channel->handling_fee} MMK",
            'balance' => auth()->user()->amount . "MMK",
            'exchange_rate' => "1 MMK = {$channel->exchange_currency->sign} {$channel->exchange_currency->buy_rate}",
            'actual_arrival' => $channel->exchange_currency->sign . " " . $request->amount * $channel->exchange_currency->buy_rate,
        ]);
    }

    public function kbzPay(KbzCreateRequest $request)
    {

        $check_passcode = (new CheckPasscode())->execute($request->passcode);

        if ($check_passcode['result'] == 0) {
            return ResponseHelpers::customResponse(422, $check_passcode['message']);
        }
        if ($check_passcode['result'] == 0) {
            return ResponseHelpers::customResponse(401, $check_passcode['message']);
        }

        $findWithdrawChannel = $this->findWithdrawChannel('KBZ Pay');
        if ($findWithdrawChannel['result'] == 0) {
            return ResponseHelpers::customResponse(422, $findWithdrawChannel['message']);
        }

        $channel = $findWithdrawChannel['data'];

        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }


        return $this->createRequest($channel, $request, ChannelPrefix::KBZ_PAY);
    }

    public function aliPay(AlipayCreateRequest $request)
    {
        $check_passcode = (new CheckPasscode())->execute($request->passcode);

        if ($check_passcode['result'] == 0) {
            return ResponseHelpers::customResponse(422, $check_passcode['message']);
        }
        if ($check_passcode['result'] == 0) {
            return ResponseHelpers::customResponse(401, $check_passcode['message']);
        }


        $findWithdrawChannel = $this->findWithdrawChannel('Alipay');
        if ($findWithdrawChannel['result'] == 0) {
            return ResponseHelpers::customResponse(422, $findWithdrawChannel['message']);
        }

        $channel = $findWithdrawChannel['data'];

        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }

        return $this->createRequest($channel, $request, ChannelPrefix::ALIPAY);
    }

    public function bankCard(BankCardCreateRequest $request)
    {
        $check_passcode = (new CheckPasscode())->execute($request->passcode);

        if ($check_passcode['result'] == 0) {
            return ResponseHelpers::customResponse(422, $check_passcode['message']);
        }
        if ($check_passcode['result'] == 0) {
            return ResponseHelpers::customResponse(401, $check_passcode['message']);
        }

        $findWithdrawChannel = $this->findWithdrawChannel('Bank Card');
        if ($findWithdrawChannel['result'] == 0) {
            return ResponseHelpers::customResponse(422, $findWithdrawChannel['message']);
        }

        $channel = $findWithdrawChannel['data'];

        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->createRequest($channel, $request, ChannelPrefix::BANK_CARD);
    }

    public function thaiBaht(ThaibahtCreaterequest $request)
    {
        $check_passcode = (new CheckPasscode())->execute($request->passcode);

        if ($check_passcode['result'] == 0) {
            return ResponseHelpers::customResponse(422, $check_passcode['message']);
        }
        if ($check_passcode['result'] == 0) {
            return ResponseHelpers::customResponse(401, $check_passcode['message']);
        }

        $findWithdrawChannel = $this->findWithdrawChannel('Thai Baht');
        if ($findWithdrawChannel['result'] == 0) {
            return ResponseHelpers::customResponse(422, $findWithdrawChannel['message']);
        }

        $channel = $findWithdrawChannel['data'];

        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->createRequest($channel, $request, ChannelPrefix::THAI_BAHT);
    }

    public function weChat(WeChatCreaterequest $request)
    {
        $check_passcode = (new CheckPasscode())->execute($request->passcode);

        if ($check_passcode['result'] == 0) {
            return ResponseHelpers::customResponse(422, $check_passcode['message']);
        }
        if ($check_passcode['result'] == 0) {
            return ResponseHelpers::customResponse(401, $check_passcode['message']);
        }

        $findWithdrawChannel = $this->findWithdrawChannel('We Chat');
        if ($findWithdrawChannel['result'] == 0) {
            return ResponseHelpers::customResponse(422, $findWithdrawChannel['message']);
        }

        $channel = $findWithdrawChannel['data'];

        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->createRequest($channel, $request, ChannelPrefix::WE_CHAT);
    }

    private function validation($request, $channel)
    {
        $today_withdraw_request_amount = WithdrawRequest::whereDate('created_at', Carbon::today())->where('user_id', auth()->user()->id)->where('withdraw_channel_id', $channel->id)->whereIn('status', [Status::CONFIRMED, Status::REQUESTED, Status::COMPLETED])->sum('amount');
        $available_request_amount = $channel->max_daily - $today_withdraw_request_amount;

        if ($available_request_amount < $request->amount) {
            return [
                'result' => 0,
                'message' => 'The request amount is greater than the avaliable amount.',
            ];
        }

        return [
            'result' => 1,
            'message' => 'Success'
        ];
    }

    private function createRequest(WithdrawChannel $channel, $request, string $prefix)
    {
        [$invalid_log, $failed_account, $message, $difference_amount, $no_enough_amount, $new_withdraw_request_id]  = DB::transaction(function () use ($request, $channel, $prefix) {

            $user_locked = User::lockForUpdate()->find(auth()->user()->id);
            $user_total_accountr_locked = CashAccount::lockForUpdate()->whereReferenceId('USER')->first();

            [$invalid_log, $failed_account, $log_description, $difference_amount] = (new MonitorTransaction(accounts: [$user_locked, $user_total_accountr_locked]))->execute();

            if ($invalid_log) {
                return [
                    $invalid_log,
                    $failed_account,
                    $log_description,
                    $difference_amount,
                    $no_enough_amount = null,
                    $new_withdraw_request_id = null,
                ];
            }

            $withdraw_calculate_amount = $request->amount + $channel->handling_fee;
            if ($user_locked->amount < $withdraw_calculate_amount) {
                return [
                    $invalid_log,
                    $failed_account,
                    $log_description,
                    $difference_amount,
                    $no_enough_amount = true,
                    $new_withdraw_request_id = false,
                ];
            }

            $user_amount_before = $user_locked->amount;
            $user_amount_after = (float) bcsub($user_amount_before, $withdraw_calculate_amount, 4);

            $user_locked->update([
                'amount' => $user_amount_after,
            ]);

            $withdraw_request = WithdrawRequest::create([
                'user_id' => $user_locked->id,
                'withdraw_channel_id' => $channel->id,
                'reference_id' => Str::uuid(),
                'payee' => $request->payee,
                'rate' => $channel->exchange_currency->buy_rate,
                'amount' => $request->amount,
                'bank_name' => ($channel->name === 'Bank Card' || $channel->name === 'Thai Baht') ? $request->bank_name : null,
                'account_number' => $request->account_number,
                'handling_fee' => $channel->handling_fee,
            ]);

            $withdraw_request->refresh();
            $withdraw_request->update([
                'reference_id' => (new RechargeWithdrawReferenceId())->execute($prefix, $withdraw_request->sequence, 12)
            ]);

            $transaction_type = TransactionType::whereName(EnumsTransactionType::Withdraw)->first();
            $user_total_amount_before = $user_total_accountr_locked->amount;
            $user_total_amount_after = (float) bcsub($user_total_amount_before, $withdraw_calculate_amount, 4);

            $user_total_accountr_locked->update([
                'amount' => $user_total_amount_after,
            ]);

            $transaction = WithdrawTransaction::create([
                'user_id' => $user_locked->id,
                'withdraw_request_id' => $withdraw_request->id,
                'transaction_type_id' => $transaction_type->id,
                'amount' => $request->amount,
                'handling_fees' => $channel->handling_fee,
                'reference_id' => Str::uuid(),
                'remark' => 'Withdraw Request'
            ]);

            $transaction->refresh();
            $transaction->update([
                'reference_id' => (new ReferenceId())->execute('RC', $transaction->id),
            ]);

            (new LogTransaction(
                $transaction->history(),
                [
                    // For User Total Account
                    'historiable_id' => $user_total_accountr_locked->id,
                    'historiable_type' => get_class($user_total_accountr_locked),
                    'transactionable_id' => $transaction->id,
                    'transactionable_type' => get_class($transaction),
                    'transaction_type_id' => $transaction_type->id,
                    'reference_id' => $withdraw_request->reference_id,
                    'transaction_amount' => $withdraw_calculate_amount,
                    'amount_before_transaction' => $user_total_amount_before,
                    'amount_after_transaction' => $user_total_amount_after,
                    'is_from' => 0,
                    'created_at' => Carbon::now(),
                ],
                $transaction->history(),
                [
                    // For User
                    'historiable_id' => $transaction->user_id,
                    'historiable_type' => get_class(User::find($transaction->user_id)),
                    'transactionable_id' => $transaction->id,
                    'transactionable_type' => get_class($transaction),
                    'transaction_type_id' => $transaction_type->id,
                    'reference_id' => $withdraw_request->reference_id,
                    'transaction_amount' =>  $withdraw_calculate_amount,
                    'amount_before_transaction' => $user_amount_before,
                    'amount_after_transaction' => $user_amount_after,
                    'is_from' => 0,
                    'created_at' => Carbon::now(),
                ]
            ))->execute();

            $account_name = $user_locked->name;
            $account_phone_number = $user_locked->phone_number;
            $date = now()->format('Y-m-d H:i:s');

            // For Telegram Bot
            Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
                'chat_id' => TelegramConstant::chat_id,
                'text' =>  'Withdraw Requested' . "(" . $channel->name . ")" . PHP_EOL .
                    'Account Name - ' . $account_name . PHP_EOL .
                    'Phone Number - ' . $account_phone_number . PHP_EOL .
                    'Amount -' . $request->amount . PHP_EOL .
                    'Request Id -' . $withdraw_request->reference_id . PHP_EOL .
                    'Date -' . $date
            ]);

            // For RealTime GameDashboard
            $this->endpoint->handle(config('api.url.socket'), ServerPath::GET_WITHDRAW_REQUEST, [
                'withdrawRequest' => [
                    "id" => $withdraw_request->id,
                    "new" => true,
                    "count" =>  WithdrawRequest::where('status', Status::REQUESTED)->count()
                ],
            ]);

            return [
                $invalid_log,
                $failed_account,
                $log_description,
                $difference_amount,
                $no_enough_amount = false,
                $withdraw_request->id,
            ];
        }, 5);

        if ($no_enough_amount) {
            throw new Exception(__('withdraw.balance_not_enough'));
        }

        if ($invalid_log) {
            $has_log =  $failed_account->monitor_logs->where('reference_id', $invalid_log->reference_id)->first();

            if (!$has_log) {
                $monitor_log = $failed_account->monitor_logs()->create([
                    'transaction_type_id' => $invalid_log->transaction_type_id,
                    'reference_id' => $invalid_log->reference_id,
                    'transaction_at' => $invalid_log->created_at,
                    'error_text' => $message,
                    'difference_amount' => $difference_amount
                ]);

                $count = MonitorLog::where('error_status', ErrorLogStatus::PENDING)->count();

                $this->endpoint->handle(config('api.url.socket'), ServerPath::NOTI_FOR_MONITOR_LOG_REQUEST, [
                    'notiForMonitorLogRequest' => [
                        "id" => $monitor_log->id,
                        "new" => true,
                        "count" => $count
                    ]
                ]);

                if (get_class($failed_account) == "App\Models\User") {
                    if ($failed_account->frozen_at == null) {
                        $failed_account->frozen_at = now();
                        $failed_account->save();

                        return ResponseHelpers::customResponse(422, "Founding invalid transaction and you are account is  tempory freezed.");
                    }
                }
            }

            return ResponseHelpers::customResponse(422, "Founding invalid transaction and tempory unavailable.");
        }

        $new_withdraw_request = WithdrawRequest::find($new_withdraw_request_id);
        return $this->responseSucceed([
            'time' => $new_withdraw_request->created_at->format('H:i:s'),
            'payee' => $new_withdraw_request->payee,
            'withdraw_amount' => $new_withdraw_request->amount . "MMK ",
            'handling_fee' => $new_withdraw_request->handling_fee,
        ]);
    }
}
