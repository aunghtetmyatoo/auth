<?php

namespace App\Http\Requests\Api\WithdrawRequest;

use App\Models\WithdrawChannel;
use App\Actions\DevelopmentValidator;
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

        (new DevelopmentValidator())->handle([
            // 'device_id' => ['required', 'string', 'max:32'],
            // 'session_id' => ['required', 'string', 'max:60'],
            'passcode' => ['required', 'string'],
            // 'amount' => ['required', 'numeric', 'integer'],
        ]);
        return [
            'payee'=>['required','string'],
            'amount' => ['required', 'numeric'],
            'amount' => ['min:' . $channel->min_per_transaction, 'max:' . $channel->max_per_transaction],

        ];
    }
    public function messages()
    {
        $channel = WithdrawChannel::whereName('KBZ Pay')->first();
        return [
            'amount.min' => __('channels/withdraw.failed.min_per_transaction', [
                'min_amount' => $channel->min_per_transaction,
            ]),
            'amount.max' => __('channels/withdraw.failed.max_per_transaction', [
                'max_amount' => $channel->max_per_transaction,
            ]),
        ];
    }
}
