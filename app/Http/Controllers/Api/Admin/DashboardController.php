<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\Shop;
use App\Models\ShopInventory;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function inventorySummary(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $shop = $user->shop;

        $hasProfileSetup = (bool)$shop;
        $shopStatus = $shop ? $shop->status : null;

        $total = $shop ? $shop->shopInventories()->count() : 0;
        $inStock = $shop ? $shop->shopInventories()->where('stock_status', 'in_stock')->count() : 0;
        $lowStock = $shop ? $shop->shopInventories()->where('stock_status', 'low_stock')->count() : 0;

        return response()->json([
            'hasProfileSetup' => $hasProfileSetup,
            'shop_status' => $shopStatus,
            'total_products' => $total,
            'in_stock' => $inStock,
            'low_stock' => $lowStock,
        ]);
    }

    public function adminOverview(): \Illuminate\Http\JsonResponse
    {
        $totalRegisteredUsers = User::where('role', '!=', 'admin')->count();
        $totalShopOwners = User::where('role', 'shop_owner')->count();
        $activeShops       = Shop::where('status', 'active')->count();
        $totalFertilizers = Fertilizer::count();
        $totalVisitors = Visitor::distinct('ip_address')->count('ip_address');

        return response()->json([
            'total_registered_users' => $totalRegisteredUsers,
            'total_shop_owners' => $totalShopOwners,
            'active_shops'       => $activeShops,
            'total_fertilizers' => $totalFertilizers,
            'total_visitors' => $totalVisitors,
        ]);
    }

    public function totalFertilizerStock()
    {
        $stocks = DB::table('shop_inventories')
            ->join('fertilizers', 'shop_inventories.fertilizer_id', '=', 'fertilizers.id')
            ->select(
                'fertilizers.name',
                'fertilizers.category',
                DB::raw('SUM(shop_inventories.stock_quantity) as total_stock')
            )
            ->groupBy('fertilizers.id', 'fertilizers.name', 'fertilizers.category')
            ->orderByDesc('total_stock')
            ->get();

        return response()->json($stocks);
    }
}
