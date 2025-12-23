@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="fw-bold">Selamat Datang, {{ auth()->user()->name }}!</h4>
                    <p class="text-muted">Anda login sebagai: <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
                    <hr>
                    <div class="row text-center mt-4">
                        @if(auth()->user()->role == 'admin')
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h5>Management User</h5>
                                <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">Buka Modul</a>
                            </div>
                        </div>
                        @endif

                        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'supply_chain')
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h5>Inventory & Stock</h5>
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-success">Buka Modul</a>
                            </div>
                        </div>
                        @endif

                        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'kasir')
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h5>Transaksi Kasir</h5>
                                <a href="{{ route('sales.create') }}" class="btn btn-sm btn-info text-white">Buka Modul</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection