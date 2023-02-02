<?php

namespace App\Http\Controllers\Admin;

use App\Models\WithdrawChannel;
use App\Traits\Auth\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Admin\WithdrawChannel\WithdrawChannelCreateRequest;
use App\Http\Requests\Backend\Admin\WithdrawChannel\WithdrawChannelDeleteRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Backend\Admin\WithdrawChannel\WithdrawChannelResource;
use App\Http\Resources\Backend\Admin\WithdrawChannel\WithdrawChannelCollection;
use App\Http\Requests\Backend\Admin\WithdrawChannel\WithdrawChannelIndexRequest;
use App\Http\Requests\Backend\Admin\WithdrawChannel\WithdrawChannelShowRequest;
use App\Http\Requests\Backend\Admin\WithdrawChannel\WithdrawChannelUpdateRequest;

class WithdrawChannelCrudController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WithdrawChannelIndexRequest $request)
    {
        $channels = WithdrawChannel::where(function ($query) use ($request) {

            $request->has('id') &&
                $query->whereIn("id", $request->id);
        });

        return $this->responseCollection(new WithdrawChannelCollection($channels->paginate(5)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WithdrawChannelCreateRequest $request)
    {
        $recharge_channel = WithdrawChannel::firstOrCreate([
            'name' => $request->name,
            'exchange_currency_id' => $request->exchange_currency_id,
            'min_per_transaction' => $request->min_per_transaction ? $request->min_per_transaction : 1,
            'max_per_transaction' => $request->max_per_transaction ? $request->max_per_transaction : 1000000,
            'max_daily' => $request->max_daily ? $request->max_daily : 1000000,
            'handling_fee' => $request->handling_fee ? $request->handling_fee : 0,
            'telegram_channel_id' => $request->telegram_channel_id,
            'status' => $request->status ? $request->status : 1,
            'icon_active' => $request->icon_active ? $request->icon_active : null,
            'icon_inactive' => $request->icon_inactive ? $request->icon_inactive : null,
        ]);

        return $this->responseSucceed(
            data: [
                'recharge_channel' => new WithdrawChannelResource($recharge_channel),
            ],
            status_code: Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(WithdrawChannelShowRequest $request)
    {
        $recharge_channel = WithdrawChannel::findOrFail($request->id);

        return $this->responseSucceed(
            data: [
                'recharge_channel' => new WithdrawChannelResource($recharge_channel),
            ],
            status_code: Response::HTTP_OK,
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WithdrawChannelUpdateRequest $request)
    {
        $recharge_channel = WithdrawChannel::findOrFail($request->id);

        $recharge_channel->update([
            'name' => $request->name,
            'exchange_currency_id' => $request->exchange_currency_id,
            'min_per_transaction' => $request->min_per_transaction ? $request->min_per_transaction : 1,
            'max_per_transaction' => $request->max_per_transaction ? $request->max_per_transaction : 1000000,
            'max_daily' => $request->max_daily ? $request->max_daily : 1000000,
            'handling_fee' => $request->handling_fee ? $request->handling_fee : 0,
            'telegram_channel_id' => $request->telegram_channel_id,
            'status' => $request->status ? $request->status : 1,
            'qr_code' => $request->qr_code ? $request->qr_code : null,
            'icon_active' => $request->icon_active ? $request->icon_active : null,
            'icon_inactive' => $request->icon_inactive ? $request->icon_inactive : null,
        ]);

        return $this->responseSucceed(status_code: Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(WithdrawChannelDeleteRequest $request)
    {
        $recharge_channel = WithdrawChannel::findOrFail($request->id);
        $recharge_channel->delete();

        return $this->responseSucceed(status_code: Response::HTTP_NO_CONTENT);
    }
}
