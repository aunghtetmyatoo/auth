<?php

namespace App\Http\Controllers\RechargeRequest;

use App\Models\User;
use App\Constants\Status;
use App\Actions\StoreFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constants\ServerPath;
use App\Actions\HandleEndpoint;
use App\Models\RechargeChannel;
use App\Models\RechargeRequest;
use App\Traits\Auth\ApiResponse;
use App\Constants\TelegramConstant;
use App\Actions\GenerateReferenceId;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Api\Enquiry\UsdtRequest;
use App\Exceptions\ServiceUnavailableException;
use App\Http\Resources\Recharge\RechargeResource;
use App\Http\Resources\Recharge\RechargeCollection;
use App\Http\Requests\Api\RechargeRequest\RechargeCreateRequest;

class RechargeRequestController extends Controller
{
    use ApiResponse;

    public function __construct(private HandleEndpoint $handleEndpoint)
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

        return new RechargeCollection ($rechargeRequest->orderBy('created_at', 'DESC')->paginate($request->perPage ? $request->perPage : 10 ));
        // return new RechargeCollection($rechargeRequest->paginate($request->perPage ? $request->perPage : 5));
    }
    public function enquiryUsdt(UsdtRequest $request)
    {
        $channel = $this->validation('USDT');
        $ustd_amount = (float)($request->amount) * ($channel->exchange_currency->sell_rate);

        return $this->responseSucceed([
            'recharge_amount' => number_format($request->amount),
            'code' => $ustd_amount . 'USDT.TRC20',
            'usdt_amount' => $ustd_amount,

        ]);
    }

    public  function usdt(RechargeCreateRequest $request)
    {
        $channel = $this->validation('USDT');
        $store_file = new StoreFile('Image/Recharge' . $request->user_id);
        $transaction_screenshot_path = $store_file->execute(file: $request->file('screenshot'), file_prefix: Status::RECHARGE);

        $rechargeRequest = RechargeRequest::create([
            "user_id" => auth()->user()->id,
            "screenshot" => $transaction_screenshot_path,
            "requested_amount" => $request->amount,
            'reference_id' => Str::uuid(),
            "recharge_channel_id" => $channel->id,
            "expired_at" => now()->addMinutes(30),


        ]);

        // For RealTime GameDashboard
        $this->handleEndpoint->handle(server_path: ServerPath::GET_RECHARGE_REQUEST, request: [
            'rechargeRequest' =>  new RechargeResource(RechargeRequest::findOrFail($rechargeRequest->id))
        ]);
        $auth_user = auth()->user();
        $account_name = $auth_user->name;
        $account_phone_number = $auth_user->phone_number;

        // For Telegram Bot
        Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
            'chat_id' => TelegramConstant::chat_id,
            'text' =>   'Account Name = ' . $account_name . PHP_EOL .
                'Account Number = ' . $account_phone_number . PHP_EOL .
                'Amount =' . $request->amount,
        ]);

        return $this->responseSucceed(message: "Add Recharge Request Successfully");
    }

    private function validation(string $channel_name)
    {
        $channel = RechargeChannel::whereName($channel_name)->first();
        // if (!$channel->status) {
        //     throw new ServiceUnavailableException();
        // }
        // if (RechargeRequest::where('user_id', auth()->user()->id)->where('recharge_channel_id', $channel->id)->where('status', Status::REQUESTED)->exists()) {
        //     throw new ServiceUnavailableException();
        // }
        return $channel;
    }
}
