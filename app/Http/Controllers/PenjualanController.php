<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('stock', '!=', 0)->get();
        return view('transactions.penjualan.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('stock', '!=', null)->get();
        return view('transactions.sales.create', compact('products', 'transaction'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'quantity' => 'required',
            'product_id' => 'required',
        ]);

        $transaction = Transaction::create([
            'type_id' => 1,
            'user_id' => Auth::user()->id,
            'date' => $request->date,
            'voucher' => 'TxPay' . date('mdHis'),
            'description' => $request->description,
        ]);

        $quantities = collect($request->quantity)
                        ->map( function($quantity) {
                            return ['quantity' => $quantity];
                        } );

        $transaction->products()->sync($quantities);

        $data = $transaction->load('products');

        $totalHarga = 0;

        foreach ($transaction->products as $product) {
            $harga = $product->price * $product->pivot->quantity;
            $totalHarga = $harga;
        }

        return [$totalHarga, $data];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = Transaction::where('id', $id)->with('products')->first();
        // dd($transaction);
        $products = Product::where('stock', '!=', null)->get();
        return view('transactions.penjualan.edit', compact('products', 'transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        $request->validate([
            'date' => 'required',
            'voucher' => 'required',
            'payment' => 'required',
            'refund' => 'required',
            'quantity' => 'required',
            'product_id' => 'required',
        ]);

        $transaction->update([
            'date' => $request->date,
            'type_id' => 1,
            'user_id' => Auth::user()->id,
            'voucher' => 'TxPay' . date('mdHis'),
            'payment' => $request->payment,
            'refund' => $request->refund,
            'description' => $request->description,
        ]);

        $quantities = collect($request->quantity)
                        ->map( function($quantity) {
                            return ['quantity' => $quantity];
                        } );

        $transaction->products()->sync($quantities);

        return redirect('/penjualan')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
    }

    public function payment(Request $request)
    {
        $request->validate([
            'pembayaran' => 'required',
            'kembalian' => 'required',
        ]);

        $transaction = Transaction::find($request->id);

        $transaction->payment = $request->pembayaran;
        $transaction->refund = $request->kembalian;
        $transaction->save();

        return response()->json($transaction);

    }

    public function getPenjualan(Request $request)
    {
        $penjualan = Transaction::where('id', $request->id)->with(['products', 'user'])->first();
        return $penjualan;

    }

}
