<?php

namespace App\Http\Controllers;

use App\Models\ItemPembelian;
use App\Models\Pembelian;
use App\Models\Product;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cashier.index', compact('products', 'cart', 'total'));
    }


    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cashier.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cashier.index')->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'LIKE', "%{$query}%")->get();

        return response()->json($products);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        if ($request->payment_method == 'cash' && $request->cash_paid < $total) {
            return redirect()->back()->with('error', 'Jumlah uang tunai tidak mencukupi untuk melakukan pembayaran.');
        }

        $pembelian = Pembelian::create([
            'payment_method' => $request->payment_method,
            'total' => $total,
            'cash_paid' => $request->cash_paid ?? null,
            'change_due' => $request->cash_paid ? $request->cash_paid - $total : null,
        ]);

        foreach ($cart as $id => $item) {
            $product = Product::find($id);

            if ($product) {
                $product->stock -= $item['quantity'];
                $product->save();

                ItemPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }

        session()->forget('cart');

        return redirect()->route('cashier.receipt', $pembelian->id)->with('success', 'Checkout berhasil!');
    }


    public function receipt($id)
    {
        $pembelian = Pembelian::with('itemPembelian.product')->findOrFail($id);
        return view('cashier.receipt', compact('pembelian'));
    }
}
