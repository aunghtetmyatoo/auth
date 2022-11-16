<?php

namespace App\Http\Requests\Api\Auth\Payment;

use App\Actions\DevelopmentValidator;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
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
        (new DevelopmentValidator())->handle([
            'device_id' => ['required', 'string'],
        ]);

        return [
            'payment_type_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric'],
            'transaction_datetime' => ['date'],
            'transaction_ss' => ['required', 'image', 'mimes:png,jpg,jpeg'],
        ];
    }
}
