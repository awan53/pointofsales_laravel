@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Detail Penjualan #{{ $sale->invoice }}</h4>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h6 class="mb-3">Informasi Penjualan:</h6>
                    <div><strong>Tanggal:</strong> {{ $sale->created_at->format('d M Y H:i') }}</div>
                    <div><strong>Metode:</strong> <span class="badge bg-info text-dark">{{ strtoupper($sale->payment_method) }}</span></div>
                    <div>
                        <strong>Status:</strong> 
                        @if($sale->status == 'success')
                            <span class="badge bg-success">Selesai / Lunas</span>
                        @elseif($sale->status == 'pending')
                            <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                        @else
                            <span class="badge bg-danger">Gagal</span>
                        @endif
                    </div>
                </div>
                
                {{-- BAGIAN TOMBOL BAYAR QRIS --}}
                <div class="col-sm-6 text-end">
                    @if($sale->payment_method == 'qris' && $sale->status == 'pending')
                        <h6 class="mb-3">Aksi Pembayaran:</h6>
                        <button id="pay-button" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-qrcode me-2"></i>Bayar dengan QRIS
                        </button>
                    @endif
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total Bayar</th>
                        <th class="text-end text-primary">Rp {{ number_format($sale->total, 0, ',', '.') }}</th>
                    </tr>
                    @if($sale->payment_method == 'cash')
                    <tr>
                        <th colspan="4" class="text-end">Tunai</th>
                        <th class="text-end">Rp {{ number_format($sale->paid, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">Kembalian</th>
                        <th class="text-end">Rp {{ number_format($sale->change, 0, ',', '.') }}</th>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Load Midtrans Snap JS --}}
{{-- Gunakan app.midtrans.com jika sudah production --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function () {
            // SnapToken diambil dari kolom snap_token di database
            window.snap.pay('{{ $sale->snap_token }}', {
                onSuccess: function(result) {
                    alert("Pembayaran Berhasil!");
                    location.reload();
                },
                onPending: function(result) {
                    alert("Menunggu Pembayaran...");
                    location.reload();
                },
                onError: function(result) {
                    alert("Pembayaran Gagal!");
                    location.reload();
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                }
            });
        });
    }
</script>
@endsection