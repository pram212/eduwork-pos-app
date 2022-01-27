<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Activity;

class SupplierController extends Controller
{
    public function index()
    {
        $this->authorize('lihat supplier');

        return view('suppliers');

    }


    public function store(Request $request)
    {
        $this->authorize('tambah supplier');

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

    public function update(Request $request, Supplier $supplier)
    {
        $this->authorize('edit supplier');

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


    public function destroy(Supplier $supplier)
    {
        $this->authorize('hapus supplier');

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => "Menghapus Supplier (" . $supplier->company_name.")",
        ]);

        $supplier->delete();

        return response()->json($supplier);
    }

}
