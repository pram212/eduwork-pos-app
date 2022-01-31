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
        $this->authorize('lihat penjualan');

        $products = Product::where('stock', '>', 0)->get();

        // testing mode
        return view('transactions.sales.sales-test', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('tambah penjualan');

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
        $this->authorize('tambah penjualan');

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

        $sale = new Sale();
        $sale->total_price = $this->unFormatCurrency($request->total);
        $sale->payment = $request->pembayaran;
        $sale->code = "SO" . date('ymdhis');
        $sale->save();

        $sale->products()->sync($products);

        // kurangi stok sesuai quantity dari sale yang beru saya disimpan
        foreach ($sale->products as $p) {
            $product = Product::find($p->id);
            $product->stock -= $p->pivot->quantity;
            $product->save();
        }

        return response()->json( $sale->load('products') );
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
        // handle ajax untuk mengambil satu data penjualan untuk edit
        return response()->json( $sale->load('products') );
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
        $this->authorize('edit penjualan');

        $request->validate([
            'total' => ['required'],
            'pembayaran' => ['required'],
            'produk' => ['required'],
            'quantity' => ['required']
        ]);

        // return $sale->load('products');

        // kembalikan stok produk ke semula
        foreach ($sale->products as $p) {
                $product = Product::find($p->id);
                $product->stock += $p->pivot->quantity;
                $product->save();
        }

        // isi tabel sales
        $sale->total_price = $this->unFormatCurrency($request->total);
        $sale->payment = $request->pembayaran;
        $sale->save();

        // maping request produk sesuai dengan tempate method sync cth: $user->roles()->sync([1 => ['expires' => true], 2, 3]);
        $products = [];
        foreach ($request->produk as $key => $value) {
            $products[$value] = ["quantity" => $request->quantity[$key] ];
        }

        //  isi tabel pivot dengan method sync
        $sale->products()->sync($products);

        // kurangi stok sesuai quantity dari sale yang beru saya disimpan
        foreach ($request->produk as $key => $value) {
            $product = Product::find($value);
            $product->stock -= $request->quantity[$key];
            $product->save();
        }

        return response()->json( $sale->load('products') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        $this->authorize('hapus penjualan');

        $sale->products()->detach();

        $sale->delete();

        return response("Penjualan berhasil dihapus");
    }

    public function getSale($id)
    {
        $this->authorize('lihat penjualan');

        $sale = Sale::where('id', $id)->with('products')->first();

        return response()->json($sale);
    }

    public function getProducts()
    {
        $products = Product::where('stock', '>', 0)->get();
        return $products;
    }

    public function unFormatCurrency($num)
    {
        return intval(preg_replace('/[^\d\.]/', '', $num));
    }

}
