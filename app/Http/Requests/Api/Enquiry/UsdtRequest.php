<?php

namespace App\Http\Requests\Api\Enquiry;

use App\Models\RechargeChannel;
use App\Actions\DevelopmentValidator;
use Illuminate\Foundation\Http\FormRequest;

class UsdtRequest extends FormRequest
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
            'amount' => ['required','numeric'],
        ];
    }
}
