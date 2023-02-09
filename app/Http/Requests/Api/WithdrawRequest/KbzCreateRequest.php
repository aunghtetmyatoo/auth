<?php

namespace App\Http\Requests\Api\WithdrawRequest;

use App\Models\WithdrawChannel;
use Illuminate\Foundation\Http\FormRequest;

class KbzCreateRequest extends FormRequest
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
        $channel=WithdrawChannel::where('name','KBZ Pay')->first();
        return [
            'payee'=>['required','string'],
            'amount' => ['required', 'numeric'],
            'amount' => ['min:' . $channel->min_per_transaction, 'max:' . $channel->max_per_transaction],
            'passcode' => ['required', 'string'],

        ];
    }
}
