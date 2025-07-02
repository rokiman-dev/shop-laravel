<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use DB;

class LaporanController extends Controller
{
    // Laporan penjualan harian/bulanan
    public function penjualan(Request $request)
    {
        // Filter tanggal/bulan
        $tglAwal = $request->get('tgl_awal', now()->format('Y-m-01'));
        $tglAkhir = $request->get('tgl_akhir', now()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->where('status_order', 'lunas')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $orders->sum('total_order');

        return view('admin.laporan.penjualan', compact('orders', 'tglAwal', 'tglAkhir', 'total'));
    }

    // Produk terlaris
    public function produkTerlaris(Request $request)
    {
        $tglAwal = $request->get('tgl_awal', now()->format('Y-m-01'));
        $tglAkhir = $request->get('tgl_akhir', now()->format('Y-m-d'));

        $terlaris = OrderItem::select('product_id', DB::raw('SUM(qty) as total_terjual'))
            ->whereHas('order', function ($q) use ($tglAwal, $tglAkhir) {
                $q->where('status_order', 'lunas')
                    ->whereBetween('created_at', [$tglAwal, $tglAkhir]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->with('product')
            ->take(10)
            ->get();

        return view('admin.laporan.produk_terlaris', compact('terlaris', 'tglAwal', 'tglAkhir'));
    }

    // Laporan pembelian ke supplier
    public function pembelian(Request $request)
    {
        $tglAwal = $request->get('tgl_awal', now()->format('Y-m-01'));
        $tglAkhir = $request->get('tgl_akhir', now()->format('Y-m-d'));

        $purchases = Purchase::whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->with('supplier')
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $purchases->sum('total_harga');

        return view('admin.laporan.pembelian', compact('purchases', 'tglAwal', 'tglAkhir', 'total'));
    }

    // Cetak PDF/Excel (contoh PDF saja)
    public function penjualanPdf(Request $request)
    {
        // (Optional) Pakai dompdf/barryvdh/laravel-dompdf
        $tglAwal = $request->get('tgl_awal', now()->format('Y-m-01'));
        $tglAkhir = $request->get('tgl_akhir', now()->format('Y-m-d'));
        $orders = Order::whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->where('status_order', 'lunas')->get();

        $pdf = \PDF::loadView('admin.laporan.penjualan_pdf', compact('orders', 'tglAwal', 'tglAkhir'));
        return $pdf->download('laporan_penjualan_' . $tglAwal . '_sd_' . $tglAkhir . '.pdf');
    }
}
