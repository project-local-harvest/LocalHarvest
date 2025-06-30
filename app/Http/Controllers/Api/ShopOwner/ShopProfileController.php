<?php

namespace App\Http\Controllers\Api\ShopOwner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopProfileController extends Controller
{
    public function listShops()
    {
        return response()->json(Shop::with('user')->get());
    }

    public function show(Request $request)
    {
        $user = $request->user();

        $shop = Shop::where('user_id', $user->id)->first();

        if (!$shop) {
            return response()->json(['message' => 'Shop profile not Set.'], 404);
        }

        return response()->json($shop);
    }
    public function store(Request $request)
    {
        $user = $request->user();

        if (Shop::where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Shop profile already exists.'], 409);
        }

        $request->validate([
            'shop_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'owner_picture_url' => 'nullable|url',
        ]);

        $latestId = Shop::max('id') ?? 0;
        $serial = 'MHP' . now()->year . (100 + $latestId + 1);

        $shop = new Shop();
        $shop->user_id = $user->id;
        $shop->shop_serial_number = $serial;
        $shop->shop_name = $request->shop_name;
        $shop->contact_number = $request->contact_number;
        $shop->address = $request->address;
        $shop->owner_picture_url = $request->owner_picture_url;
        $shop->save();

        return response()->json([
            'message' => 'Shop profile created successfully.',
            'data' => $shop
        ], 201);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $shop = Shop::where('user_id', $user->id)->first();

        if (!$shop) {
            return response()->json(['message' => 'Shop profile not found.'], 404);
        }

        $request->validate([
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'owner_picture_url' => 'nullable|url',
        ]);

        $shop->update($request->only(['contact_number', 'address', 'owner_picture_url']));

        return response()->json([
            'message' => 'Shop profile updated successfully.',
            'data' => $shop
        ]);
    }
    public function toggleStatus($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->status = $shop->status === 'active' ? 'inactive' : 'active';
        $shop->save();

        return response()->json([
            'message' => "Shop status toggled to {$shop->status}.",
            'shop' => $shop
        ]);
    }
}
