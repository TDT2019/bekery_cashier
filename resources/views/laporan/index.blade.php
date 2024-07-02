@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="card mb-2 p-2">
        <h2>Laporan Penjualan</h2>
        <form action="{{ route('laporan.index') }}" method="GET" class="mb-3">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" id="date" name="date" class="form-control" value="{{ request('date', now()->toDateString()) }}">
                </div>
                <div class="col-md-3">
                    <label for="month" class="form-label">Bulan</label>
                    <select id="month" name="month" class="form-select">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" {{ request('month', now()->month) == $month ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="year" class="form-label">Tahun</label>
                    <select id="year" name="year" class="form-select">
                        @foreach (range(2020, now()->year) as $year)
                            <option value="{{ $year }}" {{ request('year', now()->year) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered" id="sales-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID-Pembelian</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sale->id }}</td>
                    <td class="sale-total">Rp{{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>{{ $sale->created_at }}</td>
                    <td>
                        <a href="{{ route('laporan.show', $sale->id) }}" class="btn btn-info">Detail</a>
                        <a href="{{ route('laporan.receipt', $sale->id) }}" class="btn btn-secondary">Receipt</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total Keseluruhan</th>
                    <th id="grand-total">Rp0</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let total = 0;
    document.querySelectorAll('.sale-total').forEach(function (cell) {
        let value = cell.innerText.replace('Rp', '').replace(/\./g, '').replace(',', '.');
        total += parseFloat(value);
    });
    document.getElementById('grand-total').innerText = 'Rp' + total.toLocaleString('id-ID');
});
</script>
@endsection
