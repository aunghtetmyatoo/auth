<?php

namespace App\Http\Controllers\Deposite;

use App\Actions\DeleteFile;
use App\Actions\StoreFile;
use App\Constants\Status;
use App\Exceptions\GeneralError;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Deposite\DepositeRequest;
use App\Models\Deposite;
use App\Models\User;
use App\Traits\Auth\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositeController extends Controller
{
    use ApiResponse;

    public function index(DepositeRequest $request)
    {

        $store_file = new StoreFile('Image/Deposite/'.$request->phone_number);

        $transaction_photo_path = null;
        $agent_photo_path = $store_file->execute(file: $request->file('agent_photo'),file_prefix: Status::AGENT);
        if($request->transaction_photo)
        {
            $transaction_photo_path = $store_file->execute(file :$request->file('transaction_photo'),file_prefix: Status::DEPOSITE );
        }

        DB::beginTransaction();
        try {
            Deposite::create([
                "name" => $request->name,
                "account_name" => $request->account_name,
                "phone_number" => $request->phone_number,
                "amount" => $request->amount,
                "transaction_photo" => $transaction_photo_path,
                "agent_text" => $request->agent_text,
                "agent_photo" => $agent_photo_path,
            ]);

            $user = User::lockForUpdate()->where('phone_number', $request->phone_number)->first();

            if($user)
            {
                $user->update(['amount' => $user->amount + $request->amount]);
            }
            else{
                $deleteFile  = new DeleteFile();
                $deleteFile->execute(path : $agent_photo_path);
                $deleteFile->execute(path : $transaction_photo_path);
                throw new GeneralError();
            }

        DB::commit();
        } catch (Exception $e) {
            DB::rollback();
                $deleteFile  = new DeleteFile();
                $deleteFile->execute(path : $agent_photo_path);
                $deleteFile->execute(path : $transaction_photo_path);
            throw new GeneralError();
        }

        return $this->responseSucceed(message: 'Success');
    }
}

