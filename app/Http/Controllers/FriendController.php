<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Constants\Status;
use App\Actions\HandleEndpoint;
use App\Exceptions\GeneralError;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Api\Friend\UnfriendRequest;
use App\Http\Requests\Api\Friend\FriendAddRequest;
use App\Http\Resources\Api\Friend\FriendCollection;
use App\Http\Requests\Api\Friend\FriendCancelRequest;
use App\Http\Requests\Api\Friend\FriendConfirmRequest;
use App\Http\Resources\Api\RequestFriend\RequestFriendCollection;

class FriendController extends Controller
{
    use ApiResponse;

    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }

    public function index()
    {
        $friend_list = Friend::where('user_id', auth()->user()->id)->where('confirm_status', Status::CONFIRMED_FRIEND);
        return $this->responseCollection(new FriendCollection($friend_list->paginate(5)));
    }

    public function requestList()
    {
        $request_list = Friend::where('user_id', auth()->user()->id)->where('confirm_status', Status::RECEIVED_FRIEND);
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

        // add friend socket
        return $this->handleEndpoint->handle(server_name: "real_time", prefix: "friends", route_name: "add", request: [
            'request_friend_id' => auth()->user()->id,
            'user_id' => $request->friend_id,
        ]);
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

            // confirm friend socket
            return $this->handleEndpoint->handle(server_name: "real_time", prefix: "friends", route_name: "confirm", request: [
                'request_friend_id' => $request->friend_id,
                'user_id' => auth()->user()->id,
            ]);
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

            // cancel friend socket
            return $this->handleEndpoint->handle(server_name: "real_time", prefix: "friends", route_name: "cancel", request: [
                'request_friend_id' => $request->friend_id,
                'user_id' => auth()->user()->id,
            ]);
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

            //real-time socket
            return $this->handleEndpoint->handle(server_name: "real_time", prefix: "friends", route_name: "unfriend", request: [
                'request_friend_id' => auth()->user()->id,
                'user_id' => $request->friend_id,
            ]);
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
