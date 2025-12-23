@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Sales Transaction List</h2>

    <!-- Filter -->
    <form method="GET" action="{{ route('sales.index') }}" class="row mb-4">
        <div class="col-md-3">
            <input type="date" name="start_date" class="form-control"
                   value="{{ request('start_date') }}">
        </div>

        <div class="col-md-3">
            <input type="date" name="end_date" class="form-control"
                   value="{{ request('end_date') }}">
        </div>

        <div class="col-md-3">
            <input type="text" name="search" class="form-control"
                   placeholder="Search Invoice"
                   value="{{ request('search') }}">
        </div>

        <br>

        <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-primary me-2 d-inline-flex justify-content-center align-items-center">Filter</button>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary d-inline-flex justify-content-center align-items-center">Reset</a>
        <a href="{{ route('sales.create') }}" class="btn btn-warning d-inline-flex justify-content-center align-items-center">Buat Transaksi</a>
        </div>

     
    </form>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Invoice</th>
            <th>Date</th>
            <th>Total Items</th>
            <th>Total Amount</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
        @forelse ($sales as $key => $sale)
            <tr>
                <td>{{$sales -> firstItem() +$key }}</td>
                <td>{{ $sale->invoice }}</td>
                <td>{{ $sale->sales_date }}</td>
                <td>{{ $sale->items->sum('qty') }}</td>
                <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                        Detail
                    </a>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="5" class="text-center">No sales found</td>
            </tr>
        @endforelse
        </tbody>
    </table>

  <div class="mt-3">
    {{ $sales->appends(request()->query())->links() }}
</div>
</div>
@endsection
