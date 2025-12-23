@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Buat Transaksi Penjualan Baru</h2>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>

    {{-- Pesan Error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST" id="transaction-form">
        @csrf

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered" id="sale-table">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th style="width: 120px;">Qty</th>
                            <th style="width: 180px;">Harga Satuan</th>
                            <th style="width: 180px;">Subtotal</th>
                            <th style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="item-rows">
                        </tbody>
                    <tfoot>
                        <tr class="table-secondary">
                            <td colspan="3" class="text-end"><strong>TOTAL KESELURUHAN</strong></td>
                            <td><strong id="grand-total-display">Rp 0</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <button type="button" id="add-item" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Item Produk
                </button>
            </div>
        </div>

        {{-- Form Pembayaran --}}
        <div class="row">
            <div class="col-md-5 offset-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="fw-bold">💰 Uang Dibayarkan (Tunai)</label>
                            <input type="number" name="paid" id="paid" class="form-control form-control-lg" required min="0" placeholder="0">
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold">💵 Kembalian</label>
                            <input type="text" name="change" id="change-display" class="form-control form-control-lg bg-light" readonly value="Rp 0">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-2 shadow">
                            💾 Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- TEMPLATE UNTUK BARIS BARU (Disembunyikan) --}}
<template id="item-template">
    <tr class="item-row">
        <td>
            <select name="product_id[]" class="form-control product-select" required>
                <option value="" data-price="0">-- Pilih Produk --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}" data-stock="{{ $p->stock }}">
                        {{ $p->name }} (Stok: {{ $p->stock }})
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="qty[]" class="form-control qty-input" min="1" value="1" required>
        </td>
        <td>
            <span class="price-text">Rp 0</span>
        </td>
        <td>
            <span class="subtotal-text fw-bold">Rp 0</span>
            <input type="hidden" class="subtotal-value" value="0">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm remove-row">✕</button>
        </td>
    </tr>
</template>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const itemRows = document.getElementById('item-rows');
        const addItemBtn = document.getElementById('add-item');
        const template = document.getElementById('item-template');
        const grandTotalDisplay = document.getElementById('grand-total-display');
        const paidInput = document.getElementById('paid');
        const changeDisplay = document.getElementById('change-display');

        // Fungsi format Rupiah
        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        };

        // 1. Tambah Baris Baru
        addItemBtn.addEventListener('click', function () {
            const clone = template.content.cloneNode(true);
            itemRows.appendChild(clone);
        });

        // 2. Hapus Baris
        itemRows.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
                calculateGrandTotal();
            }
        });

        // 3. Update Otomatis saat Produk/Qty berubah
        itemRows.addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select') || e.target.classList.contains('qty-input')) {
                const row = e.target.closest('tr');
                const select = row.querySelector('.product-select');
                const qtyInput = row.querySelector('.qty-input');
                const priceText = row.querySelector('.price-text');
                const subtotalText = row.querySelector('.subtotal-text');
                const subtotalValue = row.querySelector('.subtotal-value');

                // Ambil data dari attribute option
                const selectedOption = select.options[select.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                const qty = parseInt(qtyInput.value) || 0;
                const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;

                // Validasi Stok di Sisi Client
                if (qty > stock) {
                    alert(`Stok tidak mencukupi! Sisa stok: ${stock}`);
                    qtyInput.value = stock;
                }

                const subtotal = price * qty;

                // Tampilkan hasil
                priceText.innerText = formatRupiah(price);
                subtotalText.innerText = formatRupiah(subtotal);
                subtotalValue.value = subtotal;

                calculateGrandTotal();
            }
        });

        // 4. Hitung Total Keseluruhan
        function calculateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal-value').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            grandTotalDisplay.innerText = formatRupiah(total);
            calculateChange();
        }

        // 5. Hitung Kembalian
        paidInput.addEventListener('input', calculateChange);

        function calculateChange() {
            const total = parseFloat(grandTotalDisplay.innerText.replace(/[^0-9]/g, '')) || 0;
            const paid = parseFloat(paidInput.value) || 0;
            const change = paid - total;

            changeDisplay.value = formatRupiah(change >= 0 ? change : 0);
            
            // Beri warna merah jika uang kurang
            if (change < 0) {
                changeDisplay.classList.add('text-danger');
            } else {
                changeDisplay.classList.remove('text-danger');
            }
        }

        // Tambahkan satu baris otomatis saat pertama kali dibuka
        addItemBtn.click();
    });
</script>
@endsection