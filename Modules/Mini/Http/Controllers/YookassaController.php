<?php

namespace Modules\Mini\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class YookassaController extends Controller
{
    public function test(Request $request) {
        Log::debug('New request from Yookassa');
        $data = $request->json()->all();
        Log::debug(json_encode($data));
    }

    public function payment($shopIdOrName, $token) {
Log::debug('token: ' . $token);
        return view('Mini::payments.yookassa.process', compact('token', 'shopIdOrName'));
    }

    public function payment_end($shopIdOrName, $token, Request $request) {
        sleep(1); // For reliability, so that the payment system has time to update all data and we receive the current status of the payment.

        Log::debug($request->get('id'));
        Log::debug($shopIdOrName);
        Log::debug($token);
        die();
    }
}
