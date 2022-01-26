<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('stock', '!=', 0)->get();
        return view('transactions.sales.sales', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('stock', '!=', 0)->get();
        return view('transactions.sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSaleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'total' => ['required'],
            'pembayaran' => ['required'],
            'produk' => ['required'],
            'quantity' => ['required']
        ]);

        dd($request->all());

        $products = [];
        foreach ($request->produk as $key => $value) {
            $products[$value] = ["quantity" => $request->quantity[$key] ];
        }

        $sale = new Sale();
        $sale->total_price = $request->total;
        $sale->payment = $request->pembayaran;
        $sale->code = "SO" . date('ymdhis');
        $sale->save();
        $sale->products()->sync($products);

        foreach ($request->produk as $key => $value) {
            $products = Product::find($value);
            $products->stock -= $request->quantity[$key];
            $products->save();
        }

        return view('transactions.sales.nota-kontan', compact($sale));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSaleRequest  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'total' => ['required'],
            'pembayaran' => ['required'],
            'produk' => ['required'],
            'quantity' => ['required']
        ]);


        $products = [];
        foreach ($request->produk as $key => $value) {
            $products[$value] = ["quantity" => $request->quantity[$key] ];
        }

        $sale->total_price = $request->total;
        $sale->payment = $request->pembayaran;
        $sale->code = "SO" . date('ymdhis');
        $sale->save();
        $sale->products()->sync($products);
        $sale->refresh();

        return response("Penjualan Berhasil Diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        $sale->products()->detach();
        $sale->delete();

        return response("Penjualan berhasil dihapus");
    }

    public function getSale($id)
    {
        $sale = Sale::where('id', $id)->with('products')->first();
        return response()->json($sale);
    }
}
