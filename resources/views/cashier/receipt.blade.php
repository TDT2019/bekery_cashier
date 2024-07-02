@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Struk Pembelian</h2>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Pembelian ID: {{ $pembelian->id }}</h5>
        </div>
        <div class="card-body">
            <p class="card-text"><strong>Tanggal:</strong> {{ $pembelian->created_at->format('d-m-Y H:i:s') }}</p>
            <p class="card-text"><strong>Metode Pembayaran:</strong> {{ ucfirst($pembelian->payment_method) }}</p>
            @if ($pembelian->payment_method === 'cash')
                <p class="card-text"><strong>Jumlah Tunai:</strong> Rp{{ number_format($pembelian->cash_paid, 0, ',', '.') }}</p>
                <p class="card-text"><strong>Kembalian:</strong> Rp{{ number_format($pembelian->change_due, 0, ',', '.') }}</p>
            @endif
            <h4 class="mt-4">Total: Rp{{ number_format($pembelian->total, 0, ',', '.') }}</h4>

            <h5 class="mt-4">Detail Item:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembelian->itemPembelian as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="{{ route('cashier.index') }}" class="btn btn-primary">Kembali ke Kasir</a>
                <button onclick="window.print();" class="btn btn-secondary">Print</button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .container, .container * {
            visibility: visible;
        }
        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .btn {
            display: none;
        }
    }
</style>
@endsection
