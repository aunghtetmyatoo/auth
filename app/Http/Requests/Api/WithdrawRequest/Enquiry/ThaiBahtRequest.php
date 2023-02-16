<?php

namespace App\Http\Requests\Api\WithdrawRequest\Enquiry;


use App\Actions\DevelopmentValidator;
use App\Models\WithdrawChannel;
use Illuminate\Foundation\Http\FormRequest;

class ThaiBahtRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        (new DevelopmentValidator())->handle([
            'amount' => ['required', 'numeric', 'integer'],
        ]);

        $channel = WithdrawChannel::whereName('Thai Baht')->first();

        return [
            'amount' => ['min:' . $channel->min_per_transaction, 'max:' . $channel->max_per_transaction],
        ];
    }

    public function messages()
    {
        $channel = WithdrawChannel::whereName('Thai Baht')->first();
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
