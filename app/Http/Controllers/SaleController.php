<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class SaleController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Sale::with('items.product')->latest();

        if($request->filled('search')){
            $query->where('invoice', 'like', '%' .$request->search .'%');
        }

        if($request->filled('start_date')){
            $query->whereDate('sales_date', '>=',$request->start_date);
        }

        if($request->filled('end_date')){
            $query->whereDate('sales_date', '<=',$request->end_date);
        }

        $sales = $query->paginate(5);       
        
        return view('sales.index', compact('sales'));

    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)-> get();
        return view('sales.create', compact('products'));
    }

    // ... (Bagian atas controller)

public function store(Request $request)
{

    
    // Cek apakah ada item yang dikirim
    if (!$request->has('product_id')) {
        return back()->withErrors(['error' => 'Minimal harus ada 1 produk!']);
    }
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
    Config::$isSanitized = true;
    Config::$is3ds = true;


    DB::beginTransaction();
    try {
        // Ganti str_random(5) menjadi Str::random(5)
        $invoice = 'INV-' . date('YmdHis') . '-' . rand(100, 999);

        $sale = Sale::create([
            'invoice'           => $invoice,
            'sales_date'        => now(),
            'payment_method'    => $request->payment_method,
            'total'             => 0,
            'paid'              => $request->payment_method== 'qris' ? 0 : $request->paid,
            'status'            =>  $request->payment_method== 'qris' ? 'pending' : 'success',
            'change'            => 0,
        ]);

        $grandTotal = 0;
        foreach ($request->product_id as $key => $product_id) {
            $product = Product::findOrFail($product_id);
            $qty = $request->qty[$key];
            
            // Validasi Stok
            if ($product->stock < $qty) {
                throw new \Exception("Stok {$product->name} tidak mencukupi.");
            }

            $subtotal = $product->selling_price * $qty;
            $grandTotal += $subtotal;

            SaleItem::create([
                'sales_id'    => $sale->id,
                'product_id' => $product_id,
                'qty'        => $qty,
                'price'      => $product->selling_price,
                'subtotal'   => $subtotal
            ]);

            $product->decrement('stock', $qty);
        }
        $change = ($request->payment_method == 'qris') ? 0 : ($request->paid - $grandTotal);
        $sale->update(['total' => $grandTotal,
                        'change' => $change]);

        if ($request->payment_method=='qris'){
            $params = [
                'transaction_details' => [
                    'order_id' => $invoice,
                    'gross_amount' => (int)$grandTotal,
                ],
                'customer_details' => [
                    'first_name' => 'pelanggan Toko',
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $sale->update(['snap_token' => $snapToken]);

            DB::commit();
            return redirect()->route('sales.show', $sale->id);
        }

        DB::commit();
        return redirect()->route('sales.index')->with('success', 'Transaksi Berhasil!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}

public function show($id){
    $sale = \App\Models\Sale::FindOrFail($id);
    return view('sales.show', compact('sale'));
}
}
