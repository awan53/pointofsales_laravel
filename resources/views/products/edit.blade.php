@extends('layouts.app')

@section('title', 'Edit Produk: ' . $product->name)

@section('content')
<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h3 class="mb-0">Form Update Produk</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT') {{-- PENTING: Menggunakan metode PUT untuk update --}}
            
            <div class="row">
                {{-- Kolom Kategori --}}
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Kategori:</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            {{-- Memastikan kategori yang sedang diedit tetap terpilih --}}
                            <option 
                                value="{{ $category->id }}" 
                                {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- Kolom Nama Produk --}}
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nama Produk:</label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $product->name) }}"
                    >
                    @error('name') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror 
                </div>
            </div>

            <div class="row">
                {{-- Kolom Harga Beli --}}
                <div class="col-md-4 mb-3">
                    <label for="purchase_price" class="form-label">Harga Beli (Rp):</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        class="form-control @error('purchase_price') is-invalid @enderror" 
                        id="purchase_price" 
                        name="purchase_price" 
                        value="{{ old('purchase_price', $product->purchase_price) }}"
                    >
                    @error('purchase_price') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
                
                {{-- Kolom Harga Jual --}}
                <div class="col-md-4 mb-3">
                    <label for="selling_price" class="form-label">Harga Jual (Rp):</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        class="form-control @error('selling_price') is-invalid @enderror" 
                        id="selling_price" 
                        name="selling_price" 
                        value="{{ old('selling_price', $product->selling_price) }}"
                    >
                    @error('selling_price') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
                
                {{-- Kolom Satuan --}}
                <div class="col-md-4 mb-3">
                    <label for="unit" class="form-label">Satuan:</label>
                    <input 
                        type="text" 
                        class="form-control @error('unit') is-invalid @enderror" 
                        id="unit" 
                        name="unit" 
                        value="{{ old('unit', $product->unit) }}"
                    >
                    @error('unit') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>

            {{-- Kolom Stok --}}
            <div class="mb-3">
                <label for="stock" class="form-label">Stok:</label>
                <input 
                    type="number" 
                    class="form-control @error('stock') is-invalid @enderror" 
                    id="stock" 
                    name="stock" 
                    value="{{ old('stock', $product->stock) }}"
                >
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-warning">Update Produk</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection