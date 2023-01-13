<?php

namespace App\Http\Requests\Api\CashOutRequest;

use Illuminate\Foundation\Http\FormRequest;

class CashOutIndexRequest extends FormRequest
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
            'transaction_type_id' => ['required'],
            'account_name' =>  ['required', 'string'],
            'account_number' =>  ['required', 'string'],
            'amount' =>  ['required', 'string'],
            // 'user_id' => ['required', 'uuid'],
            // 'status' => ['in:REQUESTED,COMPLETED,REJECTED'],
            // 'status_updated_by' => ['required'],
        ];
    }
}
