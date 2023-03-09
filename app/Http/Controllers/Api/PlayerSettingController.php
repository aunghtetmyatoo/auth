<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Models\PlayerSetting;
use App\Services\Crypto\DataKey;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Profile\PlayerSettingCollection;
use App\Http\Requests\Api\PlayerSetting\PlayerSettingUpdateRequest;

class PlayerSettingController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $setting =  PlayerSetting::whereUserId(auth()->user()->id);

        $response = $this->responseCollection(new PlayerSettingCollection($setting));

        return response()->json((new DataKey())->encrypt(json_encode($response->getData())));
    }

    public function update(PlayerSettingUpdateRequest $request)
    {
        $setting =  PlayerSetting::whereUserId(auth()->user()->id)->whereGameTypeId($request->game_type_id)->first();

        if ($request->sound_status) {
            $setting->update([
                'sound_status' => $setting->sound_status == Status::OPEN ? Status::CLOSE : Status::OPEN,
            ]);
        }

        if ($request->vibration_status) {
            $setting->update([
                'vibration_status' => $setting->vibration_status == Status::OPEN ? Status::CLOSE : Status::OPEN,
            ]);
        }

        if ($request->challenge_status) {
            $setting->update([
                'challenge_status' => $setting->challenge_status == Status::OPEN ? Status::CLOSE : Status::OPEN,
            ]);
        }

        if ($request->friend_status) {
            $setting->update([
                'friend_status' => $setting->friend_status == Status::OPEN ? Status::CLOSE : Status::OPEN,
            ]);
        }

        $response = $this->responseSucceed(message: "Update Setting Successfully");

        return response()->json((new DataKey())->encrypt(json_encode($response->getData())));
    }
}
