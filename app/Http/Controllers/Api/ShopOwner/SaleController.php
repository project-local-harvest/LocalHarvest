<?php

namespace App\Http\Controllers\Api\ShopOwner;

use App\Http\Controllers\Controller;
use App\Models\{Sale, SaleItem, ShopInventory};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Jobs\GenerateReceiptPdf;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;




class SaleController extends Controller
{

    public function store(Request $request)
    {
        $user = $request->user();
        $shop = $user->shop;

        if (!$shop) return response()->json(['message'=>'Shop not found'],404);

        $request->validate([
            'customer_name'   => 'nullable|string|max:255',
            'customer_phone'  => 'nullable|string|max:20',
            'salesperson_name'=> 'nullable|string|max:255',
            'discount_percent'=> 'nullable|integer|min:0|max:100',
            'items'           => 'required|array|min:1',
            'items.*.fertilizer_id' => 'required|exists:fertilizers,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.unit_price'    => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $shop, $user) {

            $receiptNo = 'MHP-SR-' . now()->format('Ymd') . '-' .
                str_pad((Sale::whereDate('created_at',today())->count()+1),4,'0',STR_PAD_LEFT);

            $sale = Sale::create([
                'shop_id'          => $shop->id,
                'receipt_no'       => $receiptNo,
                'customer_name'    => $request->input('customer_name') ?: 'N/A',
                'customer_phone'   => $request->input('customer_phone') ?: '01XXX - XXXXXX',
                'salesperson_name' => $request->input('salesperson_name') ?: $user->name,
                'discount_percent' => $request->discount_percent ?? 0,
                'gross_amount'     => 0,
                'net_amount'       => 0,
            ]);

            $gross = 0;

            foreach ($request->items as $row) {
                // lock inventory row
                $inv = ShopInventory::where('shop_id',$shop->id)
                    ->where('fertilizer_id',$row['fertilizer_id'])
                    ->lockForUpdate()->first();

                if (!$inv || $inv->stock_quantity < $row['quantity']) {
                    return response()->json(['message' => 'Insufficient stock for fertilizer ' . $row['fertilizer_id']], 409);
                }

                // deduct stock
                $inv->stock_quantity -= $row['quantity'];
                $inv->updateStockStatus();

                $subtotal = $row['quantity'] * $row['unit_price'];
                $gross   += $subtotal;

                SaleItem::create([
                    'sale_id'       => $sale->id,
                    'fertilizer_id' => $row['fertilizer_id'],
                    'quantity'      => $row['quantity'],
                    'unit_price'    => $row['unit_price'],
                    'subtotal'      => $subtotal,
                ]);
            }

            $discount = $gross * ($sale->discount_percent/100);
            $net      = $gross - $discount;
            $sale->update(['gross_amount'=>$gross,'net_amount'=>$net]);

            GenerateReceiptPdf::dispatch($sale);

            return response()->json([
                'message' => 'Sale recorded & inventory updated. Receipt generation is in progress.',
                'receipt' => $sale->load('items.fertilizer:id,name,category')
            ],201);
        });
    }

    public function downloadReceipt($id)
    {
        $sale = Sale::findOrFail($id);
        $path = 'receipts/' . $sale->receipt_no . '.pdf';

        if (!Storage::disk('local')->exists($path)) {
            return response()->json(['message' => 'Receipt not found.'], 404);
        }

        return Storage::disk('local')->download($path);
    }
}
