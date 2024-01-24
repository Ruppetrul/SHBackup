<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ShopController extends Controller {
    public function create(Request $request) {
        try {
            if (Shop::where('name', $request->name)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop with this name already exist.'
                ]);
            }

            $shop = Shop::create([
                'name' => $request->name,
                'owner_id' => Auth::id(),
                'payment_status' => 'trial',
                'state' => 'not_created',
                'last_used_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => (bool) $shop
            ]);
        } catch (\Exception $exception) {
            //TODO log exception
            return response()->json([
                'success' => false,
                'message' => 'Unknown error.'
            ]);
        }
    }

    function shops() {
        $shops = Shop::where('owner_id', Auth::id())->get();
        return view('shops', ['shops' => $shops]);
    }
}
