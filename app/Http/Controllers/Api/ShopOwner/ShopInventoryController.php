<?php

namespace App\Http\Controllers\Api\ShopOwner;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\Shop;
use App\Models\ShopInventory;
use Illuminate\Http\Request;

class ShopInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found for the user.'], 404);
        }

        $inventory = $shop->shopInventories()->with('fertilizer')->get();

        return response()->json($inventory);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fertilizer_id' => 'required|exists:fertilizers,id',
            'stock_quantity' => 'required|integer|min:0',
            'price_per_unit' => 'required|numeric|min:0',
            'stock_status' => 'required|in:in_stock,low_stock,out_of_stock',
        ]);

        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found for the user.'], 404);
        }

        $inventory = $shop->shopInventories()->create($request->all());

        return response()->json($inventory, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found for the user.'], 404);
        }

        $inventory = $shop->shopInventories()->with('fertilizer')->find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory item not found.'], 404);
        }

        return response()->json($inventory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'stock_quantity' => 'sometimes|integer|min:0',
            'price_per_unit' => 'sometimes|numeric|min:0',
            'stock_status' => 'sometimes|in:in_stock,low_stock,out_of_stock',
        ]);

        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found for the user.'], 404);
        }

        $inventory = $shop->shopInventories()->find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory item not found.'], 404);
        }

        $inventory->fill($request->input());
        $inventory->save();

        return response()->json($inventory->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found for the user.'], 404);
        }

        $inventory = $shop->shopInventories()->find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory item not found.'], 404);
        }

        $inventory->delete();

        return response()->json(['message' => 'Inventory item deleted.']);
    }

    public function getAvailableFertilizers(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found for the user.'], 404);
        }

        $stockedFertilizerIds = $shop->fertilizers()->pluck('fertilizers.id');

        $availableFertilizers = Fertilizer::whereNotIn('id', $stockedFertilizerIds)->get();

        return response()->json($availableFertilizers);
    }
}
