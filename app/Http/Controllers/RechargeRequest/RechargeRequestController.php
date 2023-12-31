<?php

namespace App\Http\Controllers\RechargeRequest;

use Exception;
use App\Actions\Endpoint;
use App\Constants\Status;
use App\Actions\StoreFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constants\ServerPath;
use App\Models\RechargeChannel;
use App\Models\RechargeRequest;
use App\Constants\ChannelPrefix;
use App\Traits\Auth\ApiResponse;
use App\Constants\TelegramConstant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\CustomFunctions\ResponseHelpers;
use App\Actions\RechargeWithdrawReferenceId;
use App\Exceptions\ServiceUnavailableException;
use App\Exceptions\RechargeRequestNotExistException;
use App\Http\Resources\Api\Recharge\RechargeCollection;
use App\Http\Requests\Api\Recharge\Enquiry\EnquiryKbzRequest;
use App\Http\Requests\Api\Recharge\Enquiry\EnquiryUsdtRequest;
use App\Http\Requests\Api\RechargeRequest\RechargeCreateRequest;
use App\Http\Resources\Api\RechargeChannel\RechargeChannelCollection;

class RechargeRequestController extends Controller
{
    use ApiResponse;

    public function __construct(private Endpoint $endpoint)
    {
    }

    public function index(Request $request)
    {
        $rechargeRequest = RechargeRequest::where(function ($query) use ($request) {

            $request->has('user_name') && $request->user_name != null
                && $query->whereHas("user", function ($q) use ($request) {
                    $q->where("name", 'like', '%' . $request->user_name . '%');
                });

            $request->has('admin_name') && $request->admin_name != null
                && $query->whereHas("admin", function ($q) use ($request) {
                    $q->where("name", 'like', '%' . $request->admin_name . '%');
                });

            $request->has('payment_type') && $request->payment_type != null
                && $query->whereHas("payment_type", function ($q) use ($request) {
                    $q->whereIn("name", $request->payment_type);
                });

            $request->has('status') && $request->status != null
                && $query->whereIn('status', $request->status);
        });

        return new RechargeCollection($rechargeRequest->orderBy('created_at', 'DESC')->paginate($request->perPage ? $request->perPage : 10));
    }

    public function channels()
    {
        $channels = RechargeChannel::all();

        return $this->responseCollection(new RechargeChannelCollection($channels));
    }

    public function enquiryUsdt(EnquiryUsdtRequest $request)
    {
        $channel = $this->validation('USDT');
        $usdt_amount = ceil((float)($request->amount) * ($channel->exchange_currency->sell_rate));

        return $this->responseSucceed([
            'recharge_amount' => number_format($request->amount),
            'code' => $usdt_amount . 'USDT.TRC20',
            'usdt_amount' => $usdt_amount,
            'qr_code' => Storage::url('Image/Recharge/qr_photo.jpg')
        ]);
    }

    public function enquiryKbz(EnquiryKbzRequest $request)
    {
        $channel = $this->validation('KBZ Pay');
        $kbz_amount = (float)($request->amount) * ($channel->exchange_currency->sell_rate);

        return $this->responseSucceed([
            'recharge_amount' => number_format($request->amount),
            'code' => number_format($kbz_amount) . $channel->exchange_currency->sign,
            'qr_code' => Storage::url('Image/Recharge/qr_photo.jpg')
        ]);
    }

    public  function usdt(RechargeCreateRequest $request)
    {
        $channel = $this->validation('USDT');
        return $this->createRequest($request, $channel, ChannelPrefix::USTD);
    }

    public  function kbzPay(RechargeCreateRequest $request)
    {
        $channel = $this->validation('KBZ Pay');
        return $this->createRequest($request, $channel, ChannelPrefix::KBZ_PAY);
    }

    public function cancelledUsdt(Request $request)
    {
        return $this->cancelledRequest('USDT');
    }

    public function cancelledKbz(Request $request)
    {
        return $this->cancelledRequest('KBZ Pay');
    }

    private function validation(string $channel_name)
    {
        $channel = RechargeChannel::where('name', $channel_name)->first();

        if (!$channel->status) {
            throw new RechargeRequestNotExistException();
        }

        if (RechargeRequest::where('user_id', auth()->user()->id)->where('recharge_channel_id', $channel->id)->whereIn('status', [Status::REQUESTED, Status::CONFIRMED])->where('expired_at', '>=', now())->exists()) {
            throw new ServiceUnavailableException();
        }

        return $channel;
    }

    private function createRequest(Request $request, RechargeChannel $channel, string $prefix)
    {
        try {
            $store_file = new StoreFile('Image/Recharge' . $request->user_id);
            $transaction_screenshot_path = $store_file->execute(file: $request->file('screenshot'), file_prefix: Status::RECHARGE);

            $recharge_request = RechargeRequest::create([
                "user_id" => auth()->user()->id,
                "screenshot" => $transaction_screenshot_path,
                "requested_amount" => $request->amount,
                'reference_id' => Str::uuid(),
                "recharge_channel_id" => $channel->id,
                "rate" => $channel->exchange_currency->sell_rate,
                "expired_at" => now()->addMinutes(30),
            ]);
        } catch (Exception $e) {
            return ResponseHelpers::customResponse(422, __('channels/recharge.failed.default'));
        }

        $recharge_request->refresh();

        $recharge_request->update([
            'reference_id' => (new RechargeWithdrawReferenceId())->execute($prefix, $recharge_request->sequence, 12)
        ]);

        // For RealTime GameDashboard
        $this->endpoint->handle(config('api.url.socket'), ServerPath::GET_RECHARGE_REQUEST, [
            'rechargeRequest' => [  "id" => $recharge_request->id,
                                    "new" => true,
                                    "count" => RechargeRequest::where('status', Status::REQUESTED)->count()
                                ]
        ]);

        $auth_user = auth()->user();
        $account_name = $auth_user->name;
        $account_phone_number = $auth_user->phone_number;
        $date = now()->format('Y-m-d H:i:s');

        // For Telegram Bot
        Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
            'chat_id' => TelegramConstant::chat_id,
            'text' =>  'Recharge Requested' . "(" . $channel->name . ")" . PHP_EOL .
                'Account Name - ' . $account_name . PHP_EOL .
                'Phone Number - ' . $account_phone_number . PHP_EOL .
                'Amount -' . $request->amount . PHP_EOL .
                'Request Id -' . $recharge_request->reference_id . PHP_EOL .
                'Date -' . $date
        ]);

        return $this->responseSucceed([
            'time' => $recharge_request->created_at->format('H:i:s'),
            'payee' => $recharge_request->user->name,
            'recharge_amount' => $recharge_request->requested_amount,
        ]);
    }

    public function cancelledRequest(string $channel)
    {
        $channel = RechargeChannel::where('name', $channel)->first();

        $request_cancelled = RechargeRequest::where('user_id', auth()->user()->id)->where('recharge_channel_id', $channel->id)->where('expired_at', '>=', now())->where('status', Status::REQUESTED,)->first();

        if (!$request_cancelled) {
            throw new RechargeRequestNotExistException();
        }

        $request_cancelled->update([
            'status' => 'CANCELLED'
        ]);

        // For RealTime GameDashboard
        $this->endpoint->handle(config('api.url.socket'), ServerPath::GET_RECHARGE_REQUEST, [
            'rechargeRequest' => [  "id" => $request_cancelled->id,
                                    "new" => false, "status" =>  'CANCELLED',
                                    "count" => RechargeRequest::where('status', Status::REQUESTED)->count(),
                                    "expire_time" => '-']
        ]);

        $auth_user = auth()->user();
        $account_name = $auth_user->name;
        $account_phone_number = $auth_user->phone_number;

        $date = now()->format('Y-m-d H:i:s');

        // For Telegram Bot
        Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
            'chat_id' => TelegramConstant::chat_id,
            'text' =>
            'Recharge Cancelled' . "(" . $channel->name . ")" . PHP_EOL .
                'Account Name - ' . $account_name . PHP_EOL .
                'Account Number - ' . $account_phone_number . PHP_EOL .
                'Request Id -' . $request_cancelled->reference_id . PHP_EOL .
                'Date =' . $date
        ]);

        return $this->responseSucceed(message: "Recharge Cancelled Successfully");
    }
}
