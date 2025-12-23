@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h3 class="mb-0">Tambah Kategori Produk Baru</h3>
    </div>
    

    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        
        <div class="col-md-4 mb-3">
            <label for="name">Nama Kategori:</label><br>
        <input type="text" id="name" name="name" value="{{ old('name') }}"><br>
        @error('name')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        </div>
        
        
        <br>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
    </form>
    </div>

</div>
    

    
@endsection