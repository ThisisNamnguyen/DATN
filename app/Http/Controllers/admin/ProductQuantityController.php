<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ImportItem;
use App\Models\importProduct;
use Illuminate\Http\Request;

class ProductQuantityController extends Controller
{
    public function index(Request $request) {
        // dd($request->product_code_id);
        if (!empty($request->product_code_id)) {
            $barcode = ImportItem::where('barcode', $request->product_code_id)->first();
            $qty = ImportItem::where('barcode', $request->product_code_id)->sum('qty');
            return response()->json([
                'status' => true,
                'qty' => $qty,
                'barcode' => $barcode->barcode
            ]);
        } else {
            return response()->json([
                'status' => true,
                'qty' => [],
                'barcode' => []
            ]);
        }
    }
}
