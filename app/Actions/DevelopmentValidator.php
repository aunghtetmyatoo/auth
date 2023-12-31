<?php

namespace App\Actions;

use App\Exceptions\UnprocessableException;
use Illuminate\Support\Facades\Validator;

class DevelopmentValidator
{
    public function handle(array $rules)
    {
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            throw new UnprocessableException(errors: $validator->errors()->toArray());
        }
    }
}
