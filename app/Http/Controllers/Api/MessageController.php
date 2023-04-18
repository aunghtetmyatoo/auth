<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Constants\ServerPath;
use App\Actions\Endpoint;
use App\Exceptions\UserNotExistException;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\UserResource;
use App\Http\Requests\Api\Message\PublicMessageRequest;
use App\Http\Requests\Api\Message\PrivateMessageRequest;

class MessageController extends Controller
{
    use ApiResponse;

    public function __construct(private Endpoint $endpoint)
    {
    }

    public function publicMessage(PublicMessageRequest $request)
    {
        $user = User::find(auth()->user()->id);

        if (!$user) {
            throw new UserNotExistException();
        }

        return $this->endpoint->handle(config('api.url.socket'), ServerPath::PUBLIC_MESSAGE, [
            'from_user' => new UserResource($user),
            'message' => $request->message,
            'room_id' => $request->room_id,
        ]);
    }

    public function privateMessage(PrivateMessageRequest $request)
    {
        $users = User::find([auth()->user()->id, $request->to_user_id]);

        [0 => $from_user, 1 => $to_user] = $users;

        if (!$from_user && !$to_user) {
            throw new UserNotExistException();
        }

        return $this->endpoint->handle(config('api.url.socket'), ServerPath::PRIVATE_MESSAGE, [
            'from_user' => new UserResource($from_user),
            'message' => $request->message,
            'to_user_id' => $request->to_user_id,
        ]);
    }
}
