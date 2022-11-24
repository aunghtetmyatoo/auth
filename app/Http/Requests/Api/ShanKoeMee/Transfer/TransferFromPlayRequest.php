<?php

namespace App\Http\Requests\Api\ShanKoeMee\Transfer;

use App\Actions\DevelopmentValidator;
use Illuminate\Foundation\Http\FormRequest;

class TransferFromPlayRequest extends FormRequest
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
            'game_type_id' => ['required'],
            'amount' => ['required', 'numeric', 'min:100'],
        ];
    }
}
