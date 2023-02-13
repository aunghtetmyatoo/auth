<?php

namespace App\Http\Requests\Api\WithdrawRequest;

use App\Models\WithdrawChannel;
use App\Actions\DevelopmentValidator;
use Illuminate\Foundation\Http\FormRequest;

class BankCardCreateRequest extends FormRequest
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
        $channel = WithdrawChannel::where('name','Bank Card')->first();
        (new DevelopmentValidator())->handle([
            // 'device_id' => ['required', 'string', 'max:32'],
            // 'session_id' => ['required', 'string', 'max:60'],
            'passcode' => ['required', 'string'],
            // 'amount' => ['required', 'numeric', 'integer'],
        ]);
        return [
            'payee' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'amount' => ['min:' . $channel->min_per_transaction, 'max:' . $channel->max_per_transaction],
            'passcode' => ['required', 'string'],

        ];
    }
}
