<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $suppliers = Supplier::latest()->paginate(5);
        return view('supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([ // <-- PERBAIKAN DI SINI
        'name'    => 'required|max:255',
        'contact' => 'required|max:20',
        'email'   => 'nullable|email|max:255',
        'address' => 'nullable|max:255'
    ]);
    
    // 2. Gunakan variabel $validatedData yang sudah didefinisikan
    Supplier::create($validatedData);
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
        return view('supplier.edit', compact('supplier'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        //


         $validatedData = $request->validate([ // <-- PERBAIKAN DI SINI
        'name'    => 'required|max:255',
        'contact' => 'required|max:20',
        'email'   => 'nullable|email|max:255',
        'address' => 'nullable|max:255'
    ]);
        
        $supplier->update($validatedData);
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dibuat');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SUpplier $supplier)
    {
        //
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier Dihapus dari data');
    }
}
