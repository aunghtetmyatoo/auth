<?php

namespace App\Http\Resources\Api\User;

use App\Constants\Status;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\WinLoseMatch\WinLoseMatchResource;
use App\Http\Resources\Api\WinLoseMatch\WinLoseMatchCollection;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return [
        //     'id' => $this->id,
        //     'name' => $this->name,
        // ];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'reference_id' => $this->reference_id,
            'phone_number' => $this->phone_number,
            'is_online' => 1,
            'is_playing' => ($this->play == Status::PLAYING) ? 1 : 0,
            'amount' => $this->amount,
            'payment_account_number' => $this->payment_account_number,
            'payment_account_name' => $this->payment_account_name,
            // 'payment_type' => $this->payment_type->name,
            'role' => $this->role,
            'frozen_at' => $this->frozen_at ?  $this->frozen_at->format('Y-m-d') : '-',
            'win_lose_match' => new WinLoseMatchCollection($this->win_lose_matches),
        ];
    }
}
