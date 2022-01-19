<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
        $products = Product::where('stock', '!=', null)->get();
        return view('transactions.index', compact('types', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        $products = Product::where('stock', '!=', null)->get();

        return view('transactions.create', compact('types', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'type_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        $transaction = new Transaction();
        $transaction->date = $request->date;
        $transaction->voucher = 'Trc-' . date('dmHis') . Auth::user()->id;
        $transaction->type_id = $request->type_id;
        $transaction->user_id = Auth::user()->id;
        $transaction->save();

        $transaction->refresh();

        foreach ($request->quantity as $quantity) {
            $transaction->products()->syncWithPivotValues($request->product_id, ['quantity' =>  $quantity]);
        }
        return response()->json($transaction);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        // dd($transaction->products);
        $types = Type::all();
        $products = Product::where('stock', '!=', null)->get();
        return view('transactions.edit', compact('transaction', 'products', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransactionRequest  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
