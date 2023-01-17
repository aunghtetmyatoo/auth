<?php

namespace App\Http\Requests\Api\RechargeRequest;

use App\Constants\Status;
use Illuminate\Foundation\Http\FormRequest;

class RechargeCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'transaction_screenshot' => ['required','image', 'mimes:png,jpg,jpeg'],
            'payment_type_id' =>  ['required', 'string'],
            //  'user_id' => ['required', 'uuid'],
            // 'status' => ['in:'.Status::REQUESTED,Status::COMPLETED, Status::REJECTED],
            // 'status' => ['in:REQUESTED,COMPLETED,REJECTED'],
            // 'admin_id' =>  ['required', 'string'],

        ];
    }
}
