<?php

namespace App\Http\Controllers;
use App\Models\Activity;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('warehouses');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreWarehouseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $warehouse = new Warehouse();
        $warehouse->name = $request->name;
        $warehouse->save();

        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Menambahkan Gudang Baru (" . $warehouse->name. ")",
        ]);

        return response()->json($warehouse);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWarehouseRequest  $request
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $warehouse->update([
            'name' => $request->name,
        ]);

        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Mengubah Gudang (" . $warehouse->name. ")",
        ]);

        return response()->json($warehouse);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Menghapus Gudang (" . $warehouse->name. ")",
        ]);

        $warehouse->delete();
        return response()->json($warehouse);
    }
}
