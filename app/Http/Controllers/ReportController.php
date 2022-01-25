<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesReport()
    {
        $products = Product::all();
        return view('reports.sales.index', compact('products'));
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
       $product = Product::where('id', $request->produk)->withCount('sales')->withSum('sales', 'payment')->first();
       return $product;
    }
}
