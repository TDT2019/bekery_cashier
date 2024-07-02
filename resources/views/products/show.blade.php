@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Detail Produk</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label"><strong>Nama Produk:</strong></label>
                <p>{{ $product->name }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Deskripsi:</strong></label>
                <p>{{ $product->description }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Harga:</strong></label>
                <p>Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Stok:</strong></label>
                <p>{{ $product->stock }}</p>
            </div>
            <a class="btn btn-primary" href="{{ route('products.index') }}">Kembali ke Daftar Produk</a>
            <a class="btn btn-warning" href="{{ route('products.edit', $product->id) }}">Edit Produk</a>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')" class="btn btn-danger">Hapus Produk</button>
            </form>
        </div>
    </div>
</div>
@endsection
