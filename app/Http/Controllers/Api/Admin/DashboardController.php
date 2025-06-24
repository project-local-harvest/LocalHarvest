<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\Shop;
use App\Models\ShopInventory;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function inventorySummary(): \Illuminate\Http\JsonResponse
    {
        $total = ShopInventory::count();
        $inStock = ShopInventory::where('stock_status', 'in_stock')->count();
        $lowStock = ShopInventory::where('stock_status', 'low_stock')->count();

        return response()->json([
            'total_products' => $total,
            'in_stock' => $inStock,
            'low_stock' => $lowStock
        ]);
    }

    public function adminOverview(): \Illuminate\Http\JsonResponse
    {
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalShopOwners = User::where('role', 'shop_owner')->count();
        $totalShops = Shop::whereNotNull('shop_name')->count();
        $totalFertilizers = Fertilizer::count();

        return response()->json([
            'total_users' => $totalUsers,
            'total_shop_owners' => $totalShopOwners,
            'total_shops' => $totalShops,
            'total_fertilizers' => $totalFertilizers,
        ]);
    }

}
