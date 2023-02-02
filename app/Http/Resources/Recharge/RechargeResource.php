<?php

namespace App\Http\Resources\Recharge;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RechargeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            // 'transaction_screenshot' => $this->transaction_screenshot,
            'payment_type' => $this->payment_type->name,
            'status' => $this->status,
            'admin_name' => $this->admin_id ? $this->admin->name : '-',
            // 'time_expire' =>$this->created_at->addMinutes(30)->format('Y-m-d H:i:s'),
            'time_expire' =>Carbon::now()->greaterThan($this->created_at->addMinutes(30)) ? "Expire" : $this->created_at->addMinutes(30)->format('Y-m-d H:i:s')
            ];
    }
}
