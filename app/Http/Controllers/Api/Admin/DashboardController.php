<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\Shop;
use App\Models\ShopInventory;
use App\Models\User;
use App\Models\Visitor;
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

}
