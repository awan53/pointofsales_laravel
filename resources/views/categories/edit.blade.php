@extends('layouts.app')

@section('title', 'Edit Kategori: ' . $category->name)

@section('content')

<div class="card shadow">
    <div class="card-header bg-warning text-dark"> {{-- Mengganti warna header menjadi Warning/Kuning untuk Edit --}}
        <h3 class="mb-0">Form Edit Kategori Produk</h3>
    </div>
    
    <div class="card-body">
        
        {{-- INI ADALAH FORM UTAMA UNTUK UPDATE --}}
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT') {{-- PENTING: Menggunakan metode PUT untuk update --}}
            
            <div class="mb-3"> {{-- Menggunakan mb-3 untuk margin bottom pada div --}}
                <label for="name" class="form-label">Nama Kategori:</label>
                
                <input 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    id="name" 
                    name="name" 
                    {{-- Nilai lama diprioritaskan, jika tidak ada, gunakan nilai dari database --}}
                    value="{{ old('name', $category->name) }}"
                >
                
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div> {{-- Menggunakan class Bootstrap untuk pesan error --}}
                @enderror
            </div>
            
            <button type="submit" class="btn btn-warning">Update Kategori</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
        </form>
        
    </div>
</div>
    
@endsection