<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('suppliers');
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
     * @param  \App\Http\Requests\StoreSupplierRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $supplier = new Supplier();
        $supplier->create($request->all());

        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Menambahkan Supplier Baru (" . $request->company_name .")",
        ]);

        return response()->json($supplier);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSupplierRequest  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update([
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Mengubah Supplier (" . $request->company_name . ")",
        ]);

        return response()->json($supplier);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        Activity::create([
            'user_id' => Auth::id(),
            'activity' => "Menghapus Supplier (" . $supplier->company_name.")",
        ]);

        $supplier->delete();
        return response()->json($supplier);
    }
}
