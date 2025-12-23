@extends('layouts.app')

@section('title', 'Daftar Product')

@section('content')


<div class="card shadow">
  <div class="card-header bg-primary text-white">
       <h3 class="mb-0">Daftar Kategori Produk</h3>
    </div>

<div class="card-body">
    <a href="{{ route('categories.create') }}" class="btn btn-success mb-3">
            <i class="bi bi-plus-circle"></i> Tambah Produk Category
        </a>

     @if (session('success'))
        <div class ="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" 
            aria-label="close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-border table-striped table-hover">
            <thead class="table-drak">
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                 @forelse ($categories as $key => $category)
            <tr>
                <td>{{ $categories->firstItem() + $key }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-info text-white">Edit</a> |
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada kategori yang ditemukan.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
         <div class="d-flex justify-content-center">
                {{$categories->links()}}
            </div>
    </div>
</div>


   

</div>
    

@endsection