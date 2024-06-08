<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaymentController
{
    public function saveYookassaToken($shopId,Request $request) {
        $token = $request->post('yookassa_token');
        if (empty($token)) {
            return response()->json([
                'message' => 'Yookassa token is empty or is not a string'
            ], 400);
        }

        $result = Payment::store(
            $shopId,
            Payment::TAG_YOOKASSA,
            $token
        );

        return Redirect::route('shop.details', ['shopId' => $shopId]);
    }
}
