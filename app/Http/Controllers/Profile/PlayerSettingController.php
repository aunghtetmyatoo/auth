<?php

namespace App\Http\Controllers\Profile;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PlayerSetting\PlayerSettingIndexRequest;
use App\Http\Requests\Api\PlayerSetting\PlayerSettingUpdateRequest;
use App\Http\Resources\Api\Profile\PlayerSettingCollection;
use App\Models\PlayerSetting;
use App\Traits\Auth\ApiResponse;
use Illuminate\Http\Request;

class PlayerSettingController extends Controller
{
    use ApiResponse;

    public function index(PlayerSettingIndexRequest $request)
    {
        $setting =  PlayerSetting::where("user_id",$request->user_id)->get();

        return new PlayerSettingCollection($setting);

    }

    public function update(PlayerSettingUpdateRequest $request)
    {

        $setting =  PlayerSetting::where("user_id",$request->user_id)->where("game_type_id",$request->game_type_id)->first();

        if($request->sound_status)
        {
            $setting->sound_status = $setting->sound_status == Status::OPEN ? Status::CLOSE : Status::OPEN;
            $setting->save();
            return $this->responseSucceed(message: "Update Setting Successfully");
        }

        if($request->vibration_status)
        {
            $setting->vibration_status = $setting->vibration_status == Status::OPEN ? Status::CLOSE : Status::OPEN;
            $setting->save();
            return $this->responseSucceed(message: "Update Setting Successfully");
        }

        if($request->challenge_status)
        {
            $setting->challenge_status = $setting->challenge_status == Status::OPEN ? Status::CLOSE : Status::OPEN;
            $setting->save();
            return $this->responseSucceed(message: "Update Setting Successfully");
        }

        if($request->friend_status)
        {
            $setting->friend_status = $setting->friend_status == Status::OPEN ? Status::CLOSE : Status::OPEN;
            $setting->save();
            return $this->responseSucceed(message: "Update Setting Successfully");
        }
    }
}
