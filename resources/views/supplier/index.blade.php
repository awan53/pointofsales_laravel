@extends('layouts.app')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"> Daftar Supplier</h3>
    </div>
    <div class="card-body">
        <a href="{{ route('supplier.create') }}" class="btn btn-success mb-3"><i class="bi bi-plus-circle"></i>Add Supplier</a>

     @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
        <thead class="table-drak">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Address</th>
                <th width="150px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $key => $supplier)
            <tr>
                <td>{{ $suppliers->firstItem()+ $key}}</td>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->contact }}</td>
                <td>{{ $supplier->email }}</td>
                <td>{{ $supplier->address }}</td>
                <td>
                    <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-sm btn-warning mb-3">Edit</a>
                    <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger mb-3">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
         <div class="d-flex justify-content-center">
                {{$suppliers->links()}}
            </div>
    </div>

</div>

@endsection
