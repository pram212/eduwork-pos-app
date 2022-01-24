<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('transactions.purchases.purchases', compact('products', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier' => 'required',
            'tagihan' => 'required|numeric',
            'deadline' => 'required|date',
            'harga' => 'required|numeric',
            'produk' => 'required',
            'quantity' => 'required'
        ]);

        $products = [];
        foreach ($request->produk as $key => $value) {
            $products[$value] = ["quantity" => $request->quantity[$key] ];
        }

        $purchase = new Purchase();
        $purchase->code = "PO". date('dmYHis');
        $purchase->payment_status = "belum lunas";
        $purchase->acceptance_status = "belum diterima";
        $purchase->payment_deadline = $request->deadline;
        $purchase->product_price = $request->harga;
        $purchase->shipping_cost = $request->ongkir;
        $purchase->grand_total = $request->tagihan;
        $purchase->supplier()->associate($request->supplier);

        $purchase->save();

        $purchase->products()->sync($products);

        return response("Pembelian Berhasil Disimpan");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->products()->detach();
        $purchase->delete();

        return response("Pembelian berhasil dihapus");
    }

    public function getPurchase($id)
    {
        $purchase = Purchase::where('id', $id)->with('products')->first();
        return response()->json($purchase);
    }

    public function payment(Request $request, $id)
    {
        $request->validate([
            'pembayaran' => 'required'
        ]);

        $purchase = Purchase::find($id);
        $purchase->payment = $request->pembayaran;
        $purchase->save();

        return response("Pembayaran Berhasil!");
    }
}
