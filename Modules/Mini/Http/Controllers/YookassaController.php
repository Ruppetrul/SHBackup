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
}
