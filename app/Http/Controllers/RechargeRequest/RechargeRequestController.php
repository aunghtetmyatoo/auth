<?php

namespace App\Http\Controllers\RechargeRequest;

use App\Actions\HandleEndpoint;
use App\Actions\StoreFile;
use App\Constants\ServerPath;
use App\Constants\Status;
use App\Constants\TelegramConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RechargeRequest\RechargeCreateRequest;
use App\Http\Resources\Recharge\RechargeCollection;
use App\Http\Resources\Recharge\RechargeResource;
use App\Models\RechargeRequest;
use App\Models\User;
use App\Traits\Auth\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            && $query->whereHas("user", function ($q) use ($request)  {
                $q->where("name", 'like', '%' . $request->user_name . '%');
            });

            $request->has('admin_name') && $request->admin_name != null
            && $query->whereHas("admin", function ($q) use ($request)  {
                $q->where("name", 'like', '%' . $request->admin_name . '%');
            });

            $request->has('payment_type') && $request->payment_type != null
            && $query->whereHas("payment_type", function ($q) use ($request)  {
                $q->whereIn("name",$request->payment_type);
            });

            $request->has('status') && $request->status != null
            && $query->whereIn('status', $request->status );

        });

        return new RechargeCollection ($rechargeRequest->paginate($request->perPage ? $request->perPage : 5));
    }

    public  function createRecharge(RechargeCreateRequest $request)
    {
        $store_file = new StoreFile('Image/Recharge'.$request->user_id);
        $transaction_screenshot_path = $store_file->execute(file :$request->file('transaction_screenshot'),file_prefix: Status::RECHARGE );

        $rechargeRequest = RechargeRequest::create([
            "user_id" => auth()->user()->id,
            "transaction_screenshot" => $transaction_screenshot_path,
            "payment_type_id" => $request->payment_type_id,
            // "status" => $request->status,
            // "admin_id" => $request->admin_id,
        ]);

        // For RealTime GameDashboard
        $this->handleEndpoint->handle(server_path:ServerPath::GET_RECHARGE_REQUEST, request: [
            'rechargeRequest' =>  new RechargeResource(RechargeRequest::findOrFail($rechargeRequest->id))
        ]);

        // For Telegram Bot
        Http::post('https://api.telegram.org/bot'.TelegramConstant::bot_token.'/sendMessage', [
            'chat_id' =>TelegramConstant::chat_id,
            'text' =>   'Account Name = '.$request->account_name.PHP_EOL.
                        'Account Number = '.$request->account_number.PHP_EOL.
                        'Amount ='.$request->amount,
        ]);

        return $this->responseSucceed(message: "Add Recharge Request Successfully");
    }

}
