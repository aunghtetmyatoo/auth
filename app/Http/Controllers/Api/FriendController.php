<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Constants\Status;
use App\Actions\HandleEndpoint;
use App\Constants\ServerPath;
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

        return $this->handleEndpoint->handle(server_path: ServerPath::ADD_FRIEND, request: [
            'friend_id' => auth()->user()->id,
            'friend_name' => auth()->user()->name,
            'user_id' => $request->friend_id,
        ]);
    }

    public function confirmFriend(FriendConfirmRequest $request)
    {
        [$user, $friend] = $this->getFriendRelationship(friend_id: $request->friend_id, condition: 'not_yet_friend');

        DB::transaction(function () use ($user, $friend) {
            $user->update([
                'confirm_status' => Status::CONFIRMED_FRIEND,
            ]);

            $friend->update([
                'confirm_status' => Status::CONFIRMED_FRIEND,
            ]);
        });

        return $this->handleEndpoint->handle(server_path: ServerPath::CONFIRM_FRIEND, request: [
            'friend_id' => auth()->user()->id,
            'friend_name' => auth()->user()->name,
            'user_id' => $request->friend_id,
        ]);
    }

    public function cancelFriend(FriendCancelRequest $request)
    {
        [$user, $friend] = $this->getFriendRelationship(friend_id: $request->friend_id, condition: 'not_yet_friend');

        DB::transaction(function () use ($user, $friend) {
            $user->delete();
            $friend->delete();
        });

        return $this->responseSucceed(
            message: "Successfully canceled!.",
        );
    }

    public function unfriend(UnfriendRequest $request)
    {
        [$user, $friend] = $this->getFriendRelationship(friend_id: $request->friend_id, condition: 'friend');

        DB::transaction(function () use ($user, $friend) {
            $user->delete();
            $friend->delete();
        });

        return $this->responseSucceed(
            message: "Successfully unfriend!.",
        );
    }

    public function getFriendRelationship(string $friend_id, string $condition)
    {
        if ($condition === 'friend') {
            $user =  Friend::where('user_id', auth()->user()->id)->where('friend_id', $friend_id)->where('confirm_status', Status::CONFIRMED_FRIEND)->first();

            $friend =  Friend::where('user_id', $friend_id)->where('friend_id', auth()->user()->id)->where('confirm_status', Status::CONFIRMED_FRIEND)->first();
        }

        if ($condition === 'not_yet_friend') {
            $user =  Friend::where('user_id', auth()->user()->id)->where('friend_id', $friend_id)->where('confirm_status', Status::RECEIVED_FRIEND)->first();

            $friend =  Friend::where('user_id', $friend_id)->where('friend_id', auth()->user()->id)->where('confirm_status', Status::ADDED_FRIEND)->first();
        }

        return [$user, $friend];
    }
}
