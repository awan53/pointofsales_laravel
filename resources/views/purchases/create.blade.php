@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Purchase</h2>

    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control" required>
                <option value="">-- Choose Supplier --</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" required>
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
                <tr>
                    <td>
                        <select name="product_id[]" class="form-control">
                            @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="qty[]" class="form-control qty"></td>
                    <td><input type="number" name="price[]" class="form-control price"></td>
                    <td><input type="number" name="subtotal[]" class="form-control subtotal" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">X</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" id="add_row" class="btn btn-secondary">Add Item</button>
        <br><br>

        <button class="btn btn-primary">Save Purchase</button>
    </form>
</div>

<script>
document.getElementById('add_row').addEventListener('click', function() {
    let table = document.querySelector('#items_table tbody');
    let newRow = table.rows[0].cloneNode(true);
    newRow.querySelectorAll('input').forEach(i => i.value = '');
    table.appendChild(newRow);
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
        let row = e.target.closest('tr');
        let qty = row.querySelector('.qty').value;
        let price = row.querySelector('.price').value;
        row.querySelector('.subtotal').value = qty * price;
    }
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        if (document.querySelectorAll('#items_table tbody tr').length > 1) {
            e.target.closest('tr').remove();
        }
    }
});
</script>

@endsection
