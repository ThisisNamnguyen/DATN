<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ImportItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\importProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class ImportController extends Controller
{
    public function index(Request $request) {
        $records = importProduct::latest('id');

        if(!empty($request->get('keyword'))) {
            $records = $records->where('product_name', 'like', '%'.$request->get('keyword').'%');
        }

        $records = $records->paginate(10);

        $data['records'] = $records;
        return view('admin.records.list', $data);
    }
    public function create(Request $request) {
        return view('admin.records.create');
    }

    public function store(Request $request) {
        $rules = [
            'supplier_name' => 'required',
            'supplier_code' => 'required',
            'number' => 'required|min:1',
            // 'product_name' => 'required',
            // 'barcode' => 'required',
            // 'qty' => 'required|numeric',
            // 'import_price' => 'required|numeric',
        ];


        $validator = Validator::make($request->all(), $rules);
        $now = Carbon::now();


        if ($validator->passes()) {
            // dd($request->barcode);
            // $checkSupplier = importProduct::where('supplier_code', $request->supplier_code)->first();
            $record = new importProduct();
            $record->supplier_name = $request->supplier_name;
            $record->supplier_code = $request->supplier_code;
            $record->created_at = $now;
            $record->save();


            for($index = 0; $index < $request->number; $index++) {
                $checkProduct = ImportItem::where('barcode', $request->barcode[$index])->first();
                if ($checkProduct != null) {
                    $productAvailable = Product::where('barcode', $request->barcode[$index])->first();
                    if ($productAvailable != null) {
                        $productAvailable->qty = $productAvailable->qty + $request->qty[$index];
                        $productAvailable->save();
                    }
                }


                $import_item = new ImportItem();
                $import_item->import_products_id = $record->id;
                $import_item->product_name = $request->product_name[$index];
                $import_item->barcode = $request->barcode[$index];
                $import_item->qty = $request->qty[$index];
                $import_item->import_price = $request->import_price[$index];
                $import_item->total_price = $request->qty[$index] * $request->import_price[$index];
                $import_item->save();

            }
            $request->session()->flash('success', 'Hóa đơn đã thêm thành công');
            return response()->json([
                'status' => true,
                'message'=> "Hóa đơn đã thêm thành công"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function detail($recordId) {
        $record = importProduct::where('id', $recordId)->first();
        $importItem = ImportItem::where('import_products_id', $recordId)->get();
        $total = ImportItem::where('import_products_id', $recordId)->sum('total_price');

        if (!empty($record)) {
            return view('admin.records.detail', [
                'record' => $record,
                'importItem' => $importItem,
                'total' => $total
            ]);
        } else {
            return view('admin.records.create');
        }
    }

    public function downloadPdf($recordId) {
        $record = importProduct::where('id', $recordId)->first();
        $importItem = ImportItem::where('import_products_id', $recordId)->get();
        $total = ImportItem::where('import_products_id', $recordId)->sum('total_price');

        $data['total'] = $total;
        $data['record'] = $record;
        $data['importItem'] = $importItem;
        $pdf = Pdf::loadView('pdf.invoice', $data);
        return $pdf->download('invoice.pdf');
    }

}
