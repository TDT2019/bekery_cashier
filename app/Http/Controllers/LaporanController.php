<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::query();

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        } elseif ($request->has('month') && $request->has('year')) {
            $query->whereMonth('created_at', $request->month)
                ->whereYear('created_at', $request->year);
        } elseif ($request->has('year')) {
            $query->whereYear('created_at', $request->year);
        } else {
            $query->whereDate('created_at', now()->toDateString());
        }
        $sales = $query->get();

        return view('laporan.index', compact('sales'));
    }


    public function show($id)
    {
        $sale = Pembelian::with('itemPembelian.product')->findOrFail($id);
        return view('laporan.show', compact('sale'));
    }

    public function receipt($id)
    {
        $pembelian = Pembelian::with('itemPembelian.product')->findOrFail($id);
        return view('laporan.receipt', compact('pembelian'));
    }
}
