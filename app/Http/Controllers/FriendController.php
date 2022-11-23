<?php

namespace App\Http\Controllers;

use App\Traits\Uuid;
use App\Models\Friend;
use App\Constants\Status;
use App\Exceptions\GeneralError;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Api\Friend\UnfriendRequest;
use App\Http\Requests\Api\Friend\FriendAddRequest;
use App\Http\Resources\Api\Friend\FriendCollection;
use App\Http\Requests\Api\Friend\FriendCancelRequest;
use App\Http\Requests\Api\Friend\FriendConfirmRequest;
use App\Http\Resources\Api\RequestFriend\RequestFriendCollection;

class FriendController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $friend_list = Friend::where(function ($query) {
            $query->where('user_id', auth()->user()->id);

            $query->where('confirm_status', Status::CONFIRMED_FRIEND);
        });

        return $this->responseCollection(new FriendCollection($friend_list->paginate(5)));
    }

    public function requestList()
    {
        $request_list = Friend::where(function ($query) {
            $query->where('user_id', auth()->user()->id);

            $query->where('confirm_status', Status::RECEIVED_FRIEND);
        });

        return $this->responseCollection(new RequestFriendCollection($request_list->paginate(5)));
    }

    public function addFriend(FriendAddRequest $request)
    {
        $exist_added_friend = Friend::where('user_id', auth()->user()->id)->where('friend_id', $request->friend_id)->where('confirm_status', Status::ADDED_FRIEND)->first();

        $exist_friend = Friend::where('user_id', auth()->user()->id)->where('friend_id', $request->friend_id)->where('confirm_status', Status::CONFIRMED_FRIEND)->first();

        if ($exist_added_friend || $exist_friend) {
            throw new GeneralError();
        }

        Friend::insert([
            [
                'user_id' => auth()->user()->id,
                'friend_id' => $request->friend_id,
                'confirm_status' => Status::ADDED_FRIEND,
            ],
            [
                'user_id' => $request->friend_id,
                'friend_id' => auth()->user()->id,
                'confirm_status' => Status::RECEIVED_FRIEND,
            ],
        ]);

        //real-time socket
        $response = Http::post(config('api.server.real_time.end_point') . config('api.server.real_time.friends.prefix') . config('api.server.real_time.friends.add'), [
            'request_friend_id' => auth()->user()->id,
            'user_id' => $request->friend_id,
        ]);

        return json_decode($response);
    }

    public function confirmFriend(FriendConfirmRequest $request)
    {
        try {
            [$received_friend, $added_friend] = $this->getFriendRelationship(friend_id: $request->friend_id, condition: 'not_yet_friend');

            DB::transaction(function () use ($received_friend, $added_friend) {
                $received_friend->update([
                    'confirm_status' => Status::CONFIRMED_FRIEND,
                ]);

                $added_friend->update([
                    'confirm_status' => Status::CONFIRMED_FRIEND,
                ]);
            });

        //real-time socket
        $response = Http::post(config('api.server.real_time.end_point') . config('api.server.real_time.friends.prefix') . config('api.server.real_time.friends.confirm'), [
            'request_friend_id' => $request->friend_id,
            'user_id' => auth()->user()->id,
        ]);

        return json_decode($response);

            // return $this->responseSucceed(message: "Confirmed Friend Successfully");
        } catch (\Exception $e) {
            throw new GeneralError();
        }
    }

    public function cancelFriend(FriendCancelRequest $request)
    {
        try {
            [$received_friend, $added_friend] = $this->getFriendRelationship(friend_id: $request->friend_id, condition: 'not_yet_friend');

            DB::transaction(function () use ($received_friend, $added_friend) {
                $received_friend->delete();

                $added_friend->delete();
            });

            return $this->responseSucceed(message: "Canceled Friend Successfully");
        } catch (\Exception $e) {
            throw new GeneralError();
        }
    }

    public function unfriend(UnfriendRequest $request)
    {
        try {
            [$received_friend, $added_friend] = $this->getFriendRelationship(friend_id: $request->friend_id, condition: 'friend');

            DB::transaction(function () use ($received_friend, $added_friend) {
                $received_friend->delete();

                $added_friend->delete();
            });

            return $this->responseSucceed(message: "Unfriend Successfully");
        } catch (\Exception $e) {
            throw new GeneralError();
        }
    }

    public function getFriendRelationship(string $friend_id, string $condition)
    {
        if ($condition == 'friend') {
            $received_friend =  Friend::where('user_id', auth()->user()->id)->where('friend_id', $friend_id)->where('confirm_status', Status::CONFIRMED_FRIEND)->first();

            $added_friend =  Friend::where('user_id', $friend_id)->where('friend_id', auth()->user()->id)->where('confirm_status', Status::CONFIRMED_FRIEND)->first();
        }

        if ($condition == 'not_yet_friend') {
            $received_friend =  Friend::where('user_id', auth()->user()->id)->where('friend_id', $friend_id)->where('confirm_status', Status::RECEIVED_FRIEND)->first();

            $added_friend =  Friend::where('user_id', $friend_id)->where('friend_id', auth()->user()->id)->where('confirm_status', Status::ADDED_FRIEND)->first();
        }

        return [$received_friend, $added_friend];
    }
}
