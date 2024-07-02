@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h2>Produk</h2>
            <input type="text" id="search" class="form-control mb-4" placeholder="Cari produk...">
            <div id="product-list" class="row">
                @foreach ($products as $product)
                <div class="col-md-4 product-item">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Harga: Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                            <form action="{{ route('cashier.add_to_cart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-4">
            <h2>Keranjang</h2>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <div class="card mb-2 p-2">

                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                    @foreach ($cart as $id => $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>Rp. {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('cashier.remove_from_cart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $id }}">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
                <h4>Total: Rp. {{ number_format($total, 0, ',', '.') }}</h4>
                @if (count($cart) > 0)
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">Checkout</button>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('cashier.checkout') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Pilih Metode Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment-method" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="payment-method" name="payment_method" required>
                            <option value="cash" selected>Cash</option>
                            <option value="debit">Debit</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>
                    <div class="mb-3" id="cash-payment">
                        <label for="cash-paid" class="form-label">Jumlah Uang Tunai</label>
                        <input type="number" class="form-control" id="cash-paid" name="cash_paid">
                    </div>
                    <div class="mb-3" id="change-due">
                        <label for="change-due" class="form-label">Kembalian</label>
                        <input type="text" class="form-control" id="change-due-input" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Checkout</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default payment method is "cash"
        document.getElementById('payment-method').value = 'cash';
        document.getElementById('cash-payment').style.display = 'block';
        document.getElementById('change-due').style.display = 'block';

        const total = {{ $total }};
        const cashPaidInput = document.getElementById('cash-paid');
        const changeDueInput = document.getElementById('change-due-input');

        cashPaidInput.addEventListener('input', function() {
            const cashPaid = parseFloat(cashPaidInput.value);
            if (!isNaN(cashPaid)) {
                const changeDue = cashPaid - total;
                changeDueInput.value = 'Rp' + changeDue.toLocaleString('id-ID');
            }
        });

        document.getElementById('payment-method').addEventListener('change', function() {
            if (this.value === 'cash') {
                document.getElementById('cash-payment').style.display = 'block';
                document.getElementById('change-due').style.display = 'block';
                changeDueInput.style.display = 'block';
            } else {
                document.getElementById('cash-payment').style.display = 'none';
                document.getElementById('change-due').style.display = 'none';
                changeDueInput.style.display = 'none';
            }
        });
    });

    document.getElementById('search').addEventListener('input', function() {
        let query = this.value;

        fetch(`/cashier/search?query=${query}`)
            .then(response => response.json())
            .then(data => {
                let productList = document.getElementById('product-list');
                productList.innerHTML = '';

                data.forEach(product => {
                    productList.innerHTML += `
                        <div class="col-md-4 product-item">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text">Harga: Rp. ${Number(product.price).toLocaleString('id-ID')}</p>
                                    <form action="/cashier/add-to-cart" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="${product.id}">
                                        <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `;
                });
            });
    });
</script>
@endsection