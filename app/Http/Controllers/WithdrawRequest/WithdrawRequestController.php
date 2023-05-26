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
use App\Models\GeneralLedger;
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

        return $this->response([
            'withdraw_amount' => "{$request->amount} MMK",
            'handling_fee' => "{$channel->handling_fee} MMK",
            'balance' => auth()->user()->amount . "MMK",
            // 'exchange_rate' => "¥ 1 = {$channel->exchange_currency->sign} {$channel->exchange_currency->buy_rate}",
            'actual_arrival' => "{$request->amount} MMK",
        ], 200);
    }

    public function enquiryWechat(WeChatRequest $request)
    {
        $findWithdrawChannel = $this->findWithdrawChannel('We Chat');
        $channel = $findWithdrawChannel['data'];
        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->response([
            'withdraw_amount' => "{$request->amount} MMK",
            'handling_fee' => "{$channel->handling_fee} MMK",
            'balance' => auth()->user()->amount . "MMK",
            'exchange_rate' => "1 MMK = {$channel->exchange_currency->sign} {$channel->exchange_currency->buy_rate}",
            'actual_arrival' => $channel->exchange_currency->sign . " " . $request->amount * $channel->exchange_currency->buy_rate,
        ], 200);
    }

    public function enquiryAliPay(AliPayRequest $request)
    {
        $findWithdrawChannel = $this->findWithdrawChannel('Alipay');
        $channel = $findWithdrawChannel['data'];
        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->response([
            'withdraw_amount' => "{$request->amount} MMK",
            'handling_fee' => "{$channel->handling_fee} MMK",
            'balance' => auth()->user()->amount . "MMK",
            'exchange_rate' => "1 MMK = {$channel->exchange_currency->sign} {$channel->exchange_currency->buy_rate}",
            'actual_arrival' => $channel->exchange_currency->sign . " " . $request->amount * $channel->exchange_currency->buy_rate,
        ], 200);
    }

    public function enquiryThaiBaht(ThaiBahtRequest $request)
    {
        $findWithdrawChannel = $this->findWithdrawChannel('Thai Baht');
        $channel = $findWithdrawChannel['data'];
        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->response([
            'withdraw_amount' => "{$request->amount} MMK",
            'handling_fee' => "{$channel->handling_fee} MMK",
            'balance' => auth()->user()->amount . "MMK",
            'exchange_rate' => "1 MMK = {$channel->exchange_currency->sign} {$channel->exchange_currency->buy_rate}",
            'actual_arrival' => $channel->exchange_currency->sign . " " . $request->amount * $channel->exchange_currency->buy_rate,
        ], 200);
    }
    public function enquiryBankCard(BankCardRequest $request)
    {

        $findWithdrawChannel = $this->findWithdrawChannel('Bank Card');
        $channel = $findWithdrawChannel['data'];
        $validation = $this->validation($request, $channel);
        if ($validation['result'] == 0) {
            return ResponseHelpers::customResponse(422, $validation['message']);
        }
        return $this->response([
            'withdraw_amount' => "{$request->amount} MMK",
            'handling_fee' => "{$channel->handling_fee} MMK",
            'balance' => auth()->user()->amount . "MMK",
            'exchange_rate' => "1 MMK = {$channel->exchange_currency->sign} {$channel->exchange_currency->buy_rate}",
            'actual_arrival' => $channel->exchange_currency->sign . " " . $request->amount * $channel->exchange_currency->buy_rate,
        ], 200);
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
        $today_withdraw_request_amount = WithdrawRequest::where('user_id', auth()->user()->id)->where('withdraw_channel_id', $channel->id)->whereIn('status', [Status::CONFIRMED, Status::REQUESTED, Status::COMPLETED])->sum('amount');
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
        DB::beginTransaction();
        try {

            $auth_user = auth()->user();
            $user = User::lockForUpdate()->find($auth_user->id);

            if ($user->amount < ($request->amount + $channel->handling_fee)) {
                throw new Exception(__('withdraw.balance_not_enough'));
            }

            $user_amount_before = $user->amount;
            $user_amount_after = $user_amount_before - ($request->amount + $channel->handling_fee);

            $user->update([
                'amount' => $user_amount_after,
            ]);

            // for encryption and decryption
            // $validateResponse = (new DataKey())->validate(
            //     $request,
            //     $request->bank_name ?
            //     ['payee','bank_name','account_number','passcode','amount']:
            //     ['payee','account_number','passcode','amount']
            // );
            // if ($validateResponse['result'] == 0) {
            //     return ResponseHelpers::customResponse(422, $validateResponse['message']);
            // }
            // end encryption and decryption

            $withdraw_request = WithdrawRequest::create([
                'user_id' => $user->id,
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

            $operation_manager = Admin::lockForUpdate()->where('role', 'Operation Manager')->first();

            $om_amount_before = $operation_manager->amount;
            $om_amount_after = $om_amount_before + $request->amount + $withdraw_request->handling_fee;

            $transaction = WithdrawTransaction::create([
                'user_id' => $user->id,
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
                    // For Operation Manager
                    'historiable_id' => $operation_manager->id,
                    'historiable_type' => get_class($operation_manager),
                    'transactionable_id' => $transaction->id,
                    'transactionable_type' => get_class($transaction),
                    'transaction_type_id' => $transaction_type->id,
                    'reference_id' => $withdraw_request->reference_id,
                    'transaction_amount' => $request->amount,
                    'amount_before_transaction' => $om_amount_before,
                    'amount_after_transaction' => $om_amount_after,
                    'is_from' => 0,
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
                    'transaction_amount' => $request->amount,
                    'amount_before_transaction' => $user_amount_before,
                    'amount_after_transaction' => $user_amount_after,
                    'is_from' => 1,
                ]
            ))->execute();

            $account_name = $user->name;
            $account_phone_number = $user->phone_number;
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
                'withdrawRequest' => ["id" => $withdraw_request->id, "new" => true, "count" =>  WithdrawRequest::where('status', Status::REQUESTED)->count()],
            ]);

            DB::commit();
            return $this->response([
                // 'payee'=>$request->payee,
                // 'amount'=>$request->amount,
                // 'account_number'=>$request->account_number,
                // 'bank_name' => $request->bank_name,
                // 'passcode'=>$request->passcode

                'time' => $withdraw_request->created_at->format('H:i:s'),
                'payee' => $withdraw_request->payee,
                'withdraw_amount' => $withdraw_request->amount . "MMK ",
                'handling_fee' => $withdraw_request->handling_fee,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return ResponseHelpers::customResponse(422, $e->getMessage());
        }
    }
}
