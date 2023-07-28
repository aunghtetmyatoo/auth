<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Friend;
use App\Actions\Endpoint;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Constants\ServerPath;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Friend\UnfriendRequest;
use App\Http\Requests\Api\Friend\FriendAddRequest;
use App\Http\Requests\Api\Friend\FriendCancelRequest;
use App\Http\Requests\Api\Friend\FriendConfirmRequest;
use App\Http\Resources\Api\FindFriend\FindFriendResource;
use App\Http\Resources\Api\Friend\FriendResource;

class FriendController extends Controller
{
    use ApiResponse;

    public function __construct(private Endpoint $endpoint)
    {
    }

    public function findFriend(Request $request)
    {
        $users = User::whereNot('id', auth()->user()->id)->where(function ($query) use ($request) {
            $request->has('search') &&
                $query->where('name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('reference_id', 'like', '%' . $request->input('search') . '%');
        })->get();

        $friend_status = Friend::where('user_id', auth()->user()->id);
        foreach ($users as $user) {
            $friend_status && $friend_status = $friend_status->where('friend_id', $user->id)->value('confirm_status');
            $user->friend_status = ($friend_status == null) ? Status::NOT_FRIEND : $friend_status;
            $find_friends[] = $user;
        }

        return $this->responseCollection(FindFriendResource::collection($find_friends));
    }

    public function friendList(Request $request)
    {
        $friend_list = Friend::where('user_id', auth()->user()->id)->where('confirm_status', Status::CONFIRMED_FRIEND)->where(function ($query) use ($request) {
            $request->has('search')
                && $query->whereHas('friend', function ($queryy) use ($request) {
                    $queryy->where('name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('reference_id', 'like', '%' . $request->input('search') . '%');
                });
        })->get();

        return $this->responseCollection(FriendResource::collection($friend_list));
    }

    public function requestList(Request $request)
    {
        $request_list = Friend::where('user_id', auth()->user()->id)->where('confirm_status', Status::RECEIVED_FRIEND)->where(function ($query) use ($request) {
            $request->has('search')
                && $query->whereHas('friend', function ($queryy) use ($request) {
                    $queryy->where('name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('reference_id', 'like', '%' . $request->input('search') . '%');
                });
        })->get();

        return $this->responseCollection(FriendResource::collection($request_list));
    }

    public function addFriend(FriendAddRequest $request)
    {
        Friend::insert([
            [
                'user_id' => auth()->user()->id,
                'friend_id' => $request->friend_id,
                'confirm_status' => Status::ADDED_FRIEND,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $request->friend_id,
                'friend_id' => auth()->user()->id,
                'confirm_status' => Status::RECEIVED_FRIEND,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->endpoint->handle(config('api.url.socket'), ServerPath::FRIEND_PROCESS, [
            'user_id' => $request->friend_id,
            'event_name' => 'received_friend',
            'friend_info' => [
                'id' => auth()->user()->id,
                'name' => auth()->user()->name,
                'phone_number' => auth()->user()->phone_number,
            ],
            'status' => Status::RECEIVED_FRIEND,
        ]);

        return $this->responseSucceed(
            data: [
                'friend_id' => $request->friend_id,
                'status' => Status::ADDED_FRIEND,
            ],
            message: "Successfully added friend!.",
        );
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

        $this->endpoint->handle(config('api.url.socket'), ServerPath::FRIEND_PROCESS, [
            'user_id' => $request->friend_id,
            'event_name' => 'confirmed_friend',
            'friend_info' => [
                'id' => auth()->user()->id,
                'name' => auth()->user()->name,
                'phone_number' => auth()->user()->phone_number,
            ],
            'status' => Status::CONFIRMED_FRIEND,
        ]);

        return $this->responseSucceed(
            data: [
                'friend_id' => $request->friend_id,
                'status' => Status::CONFIRMED_FRIEND,
            ],
            message: "Successfully confirmed friend!.",
        );
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
