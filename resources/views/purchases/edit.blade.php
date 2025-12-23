@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Purchase</h2>

    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control" required>
                <option value="">-- Choose Supplier --</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}" required>
        </div>

        <hr>

        <table class="table" id="items_table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th width="100px">Qty</th>
                    <th width="150px">Price</th>
                    <th width="150px">Subtotal</th>
                    <th width="50px"></th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop data item yang sudah ada --}}
                @foreach($purchase->items as $item)
                <tr>
                    <td>
                        <select name="product_id[]" class="form-control" required>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="qty[]" class="form-control qty" value="{{ $item->qty }}" required></td>
                    <td><input type="number" name="price[]" class="form-control price" value="{{ $item->price }}" required></td>
                    <td><input type="number" name="subtotal[]" class="form-control subtotal" value="{{ $item->subtotal }}" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">X</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" id="add_row" class="btn btn-secondary">Add Item</button>
        <br><br>

        <div class="d-flex justify-content-between">
            <a href="{{ route('purchases.index') }}" class="btn btn-warning">Back</a>
            <button type="submit" class="btn btn-primary">Update Purchase</button>
        </div>
    </form>
</div>

{{-- Script tetap sama dengan Create untuk menangani baris baru dan kalkulasi --}}
<script>
document.getElementById('add_row').addEventListener('click', function() {
    let table = document.querySelector('#items_table tbody');
    let rows = table.querySelectorAll('tr');
    let newRow = rows[0].cloneNode(true); // Clone baris pertama sebagai template
    
    // Reset nilai input pada baris baru
    newRow.querySelectorAll('input').forEach(i => i.value = '');
    // Reset dropdown ke default jika perlu
    newRow.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    
    table.appendChild(newRow);
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
        let row = e.target.closest('tr');
        let qty = row.querySelector('.qty').value || 0;
        let price = row.querySelector('.price').value || 0;
        row.querySelector('.subtotal').value = qty * price;
    }
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        if (document.querySelectorAll('#items_table tbody tr').length > 1) {
            e.target.closest('tr').remove();
        } else {
            alert("At least one item is required.");
        }
    }
});
</script>
@endsection