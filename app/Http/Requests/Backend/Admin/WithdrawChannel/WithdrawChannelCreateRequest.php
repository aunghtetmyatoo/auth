<?php

namespace App\Http\Requests\Backend\Admin\WithdrawChannel;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawChannelCreateRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'exchange_currency_id' => ['required', 'integer'],
            'min_per_transaction' => ['nullable', 'numeric'],
            'max_per_transaction' => ['nullable', 'numeric'],
            'max_daily' => ['nullable', 'numeric'],
            'handling_fee' => ['nullable', 'numeric'],
            'telegram_channel_id' => ['required', 'string'],
            'status' => ['nullable', 'boolean'],
            'icon_active' => ['nullable', 'string'],
            'icon_inactive' => ['nullable', 'string'],
        ];
    }
}
