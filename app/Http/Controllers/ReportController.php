<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesReport()
    {
        $products = Product::all();
        $lapPerProduk = Product::withSum('sales as terjual', 'product_sale.quantity')
                    ->withSum('sales as penjualan', 'payment')
                    ->get();
        return view('reports.sales.index', compact('products', 'lapPerProduk'));
    }

    public function getSalesAll(Request $request)
    {
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

    public function getSalesProduct(Request $request)
    {
        # code...
        $lapPerProduk = Product::withSum('sales as terjual', 'product_sale.quantity')
                    ->withSum('sales as penjualan', 'payment')
                    ->get();

       return $lapPerProduk;
    }

    public function reportPurchasePage(Request $request)
    {
        return view('reports.purchases.index');
    }

    public function getReportPurchases(Request $request)
    {
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

}
