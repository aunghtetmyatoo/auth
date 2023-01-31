<?php

namespace App\Http\Controllers\PaymentType;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PaymentType\PaymentTypeSelectCollection;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function Select(Request $request)
    {
        $paymentType = PaymentType::where(function ($query) use ($request) {
            $request->has('name')
                && $query->where('name', 'like', '%' . $request->name . '%');
        });

            return response()->json(new PaymentTypeSelectCollection($paymentType->paginate(5)));
    }
}
