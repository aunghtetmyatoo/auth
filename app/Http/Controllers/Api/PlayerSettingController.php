<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Models\PlayerSetting;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PlayerSetting\PlayerSettingUpdateRequest;
use App\Http\Resources\Api\Profile\PlayerSettingResource;

class PlayerSettingController extends Controller
{
    use ApiResponse;

    public function show()
    {
        $setting =  PlayerSetting::where('user_id', auth()->user()->id)->first();

        return $this->responseResource(new PlayerSettingResource($setting));
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

        return $this->responseSucceed(message: "Update Setting Successfully");
    }
}
