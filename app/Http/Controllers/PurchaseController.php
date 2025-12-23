<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    //

     public function index()
    {
        $purchases = Purchase::with('supplier')->latest()->paginate(5);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products  = Product::all();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'purchase_date' => 'required',
            'product_id.*' => 'required',
            'qty.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric|min:1'
        ]);

        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'total' => 0
        ]);

        $total = 0;

        foreach ($request->product_id as $key => $product_id) {

            $qty = $request->qty[$key];
            $price = $request->price[$key];
            $subtotal = $qty * $price;
            $total += $subtotal;

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product_id,
                'qty' => $qty,
                'price' => $price,
                'subtotal' => $subtotal
            ]);

            // Update stock
            Product::where('id', $product_id)->increment('stock', $qty);
        }

        $purchase->update(['total' => $total]);

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase created successfully');
    }

    public function edit($id)
{
    // Mengambil data purchase beserta item-itemnya
    $purchase = Purchase::with('items')->findOrFail($id);
    $suppliers = Supplier::all();
    $products  = Product::all();

    return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'supplier_id' => 'required',
        'purchase_date' => 'required',
        'product_id.*' => 'required',
        'qty.*' => 'required|integer|min:1',
        'price.*' => 'required|numeric|min:1'
    ]);

    $purchase = Purchase::findOrFail($id);

    // 1. KEMBALIKAN STOK LAMA SEBELUM UPDATE
    // Sebelum menghapus item lama, kurangi stok produk sesuai qty yang pernah dibeli
    foreach ($purchase->items as $item) {
        Product::where('id', $item->product_id)->decrement('stock', $item->qty);
    }

    // 2. Hapus item pembelian lama
    $purchase->items()->delete();

    // 3. Update data Purchase (Header)
    $purchase->update([
        'supplier_id' => $request->supplier_id,
        'purchase_date' => $request->purchase_date,
    ]);

    $total = 0;

    // 4. Input Item Baru & Update Stok Baru
    foreach ($request->product_id as $key => $product_id) {
        $qty = $request->qty[$key];
        $price = $request->price[$key];
        $subtotal = $qty * $price;
        $total += $subtotal;

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product_id,
            'qty' => $qty,
            'price' => $price,
            'subtotal' => $subtotal
        ]);

        // Tambahkan stok produk dengan qty yang baru
        Product::where('id', $product_id)->increment('stock', $qty);
    }

    // 5. Update total harga terakhir
    $purchase->update(['total' => $total]);

    return redirect()->route('purchases.index')
        ->with('success', 'Purchase berhasil and menyesuaikan stock.');
}

public function destroy($id)
{
    // 1. Cari data purchase beserta itemnya
    $purchase = Purchase::with('items')->findOrFail($id);

    try {
        // Gunakan Database Transaction agar jika satu gagal, semua dibatalkan
        \DB::beginTransaction();

        // 2. KEMBALIKAN STOK (Decrement)
        // Sebelum data dihapus, kita kurangi stok barang yang pernah dibeli
        foreach ($purchase->items as $item) {
            Product::where('id', $item->product_id)->decrement('stock', $item->qty);
        }

        // 3. HAPUS ITEM DAN HEADER
        // Karena kita menggunakan Eloquent, kita bisa hapus itemnya dulu baru headernya
        $purchase->items()->delete();
        $purchase->delete();

        \DB::commit();

        return redirect()->route('purchases.index')
            ->with('success', 'Transaksi pembelian berhasil dihapus ');

    } catch (\Exception $e) {
        \DB::rollBack();
        return redirect()->route('purchases.index')
            ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
    }
}
}
