<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Sale;

class ReportController extends Controller
{
    public function salesReport()
    {
        $user = Auth::user();
        if ($user->can('lihat laporan penjualan')) {
            $products = Product::all();
            $lapPerProduk = Product::withSum('sales as terjual', 'product_sale.quantity')
                        ->withSum('sales as penjualan', 'payment')
                        ->get();
            return view('reports.sales.index', compact('products', 'lapPerProduk'));
        }

        return abort(403);
    }

    public function getSalesAll(Request $request)
    {
        $user = Auth::user();
        if ($user->can('lihat laporan penjualan')) {
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required'
            ]);

            $sales = Sale::whereDate('created_at', '>=', $request->tanggal_awal )
                            ->whereDate('created_at', '<=', $request->tanggal_akhir )
                            ->withCount('products')
                            ->get();

            return $sales;
        }
        return abort(403);
    }

    public function getSalesProduct(Request $request)
    {
        $user = Auth::user();
        if ($user->can('lihat laporan penjualan')) {
            $lapPerProduk = Product::withSum('sales as terjual', 'product_sale.quantity')
                        ->withSum('sales as penjualan', 'payment')
                        ->get();

            return $lapPerProduk;
        }
        return abort(403);
    }

    public function reportPurchasePage(Request $request)
    {
        $user = Auth::user();
        if ($user->can('lihat laporan pembelian')) {
            return view('reports.purchases.index');
        }
        return abort(403);
    }

    public function getReportPurchases(Request $request)
    {
        $user = Auth::user();
        if ($user->can('lihat laporan pembelian')) {
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required'
            ]);

            $purchases = Purchase::whereDate('created_at', '>=', $request->tanggal_awal )
                            ->whereDate('created_at', '<=', $request->tanggal_akhir )
                            ->withSum('payments as paid', 'payments.amount')
                            ->get();

            return $purchases;
        }
        return abort(403);
    }

}
