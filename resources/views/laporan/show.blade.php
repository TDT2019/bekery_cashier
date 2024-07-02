@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="card mb-2 p-2">
        <h2>Detail Penjualan (ID: {{ $sale->id }})</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Kuantitas</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->itemPembelian as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if ($sale->change_due != null)
        <h6>kembalian: Rp{{ number_format($sale->change_due, 0, ',', '.') }}</h6>
        @endif
        <h4>Total: Rp{{ number_format($sale->total, 0, ',', '.') }}</h4>
        <br>
        <a href="{{ route('laporan.index') }}" class="btn btn-primary">Kembali ke Laporan Penjualan</a>
    </div>
</div>
@endsection