<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\Visitor;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    public function listFertilizers(Request $request)
    {
        Visitor::create([
            'ip_address' => $request->ip(),
            'visited_at' => now(),
        ]);

        return response()->json(Fertilizer::all());
    }

    public function fertilizerDetails(string $id)
    {
        $fertilizer = Fertilizer::find($id);

        if (!$fertilizer) {
            return response()->json(['message' => 'Fertilizer not found.'], 404);
        }

        $shops = $fertilizer->shops()->with('user')->get()->map(function ($shop) use ($fertilizer) {
            $inventory = $shop->shopInventories()->where('fertilizer_id', $fertilizer->id)->first();
            return [
                'shop_name' => $shop->shop_name,
                'address' => $shop->address,
                'owner_name' => $shop->user->name,
                'owner_picture_url' => $shop->owner_picture_url,
                'contact_number' => $shop->contact_number,
                'price_per_unit' => $inventory->price_per_unit,
                'stock_quantity' => $inventory->stock_quantity,
                'stock_status' => $inventory->stock_status,
            ];
        });

        return response()->json([
            'fertilizer' => $fertilizer,
            'shops' => $shops,
        ]);
    }
}
