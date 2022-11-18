<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Requests\Api\Friend\FriendAddedListRequest;
use App\Http\Requests\Api\Friend\FriendAddRequest;
use App\Http\Requests\Api\Friend\FriendCancelRequest;
use App\Http\Requests\Api\Friend\FriendConfirmRequest;
use App\Http\Requests\Api\Friend\FriendIndexRequest;
use App\Http\Resources\Api\Friend\FriendCollection;
use App\Models\Friend;
use App\Traits\Auth\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    use ApiResponse;

    public function index(FriendIndexRequest $request)
    {
        $friend_list = Friend::where('user_id',$request->user_id)->where('confirm_status',Status::CONFIRMED_FRIEND)->get();

        return new FriendCollection($friend_list);

    }

    public function addFriendList(FriendAddedListRequest $request)
    {
        $added_friend_list = Friend::where('user_id',$request->user_id)->where('confirm_status',Status::ADDED_FRIEND)->get();
        return new FriendCollection($added_friend_list);
    }

    public function addFriend(FriendAddRequest $request)
    {
        Friend::insert([
            [
                'user_id' => $request->user_id,
                'friend_id' => $request->friend_id,
                'confirm_status' => Status::ADDED_FRIEND,
            ],
            [
                'user_id' => $request->friend_id,
                'friend_id' => $request->user_id,
                'confirm_status' => Status::RECEIVED_FRIEND,
            ]
        ]);

        return $this->responseSucceed(message: "Added Friend Successfully");
    }

    public function confirmFriend(FriendConfirmRequest $request)
    {
        DB::beginTransaction();
        try {
            $confirm_friend_one =  Friend::where('user_id',$request->user_id)->where('friend_id',$request->friend_id)->firstOrFail();
            $confirm_friend_one->update([
                'confirm_status' => Status::CONFIRMED_FRIEND,
                ]);

            $confirm_friend_two =  Friend::where('user_id',$request->friend_id)->where('friend_id',$request->user_id)->firstOrFail();
            $confirm_friend_two->update([
                'confirm_status' => Status::CONFIRMED_FRIEND,
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }

        return $this->responseSucceed(message: "Confirmed Friend Successfully");
    }
}
