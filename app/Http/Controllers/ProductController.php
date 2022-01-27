<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $this->authorize('view');

        $categories = Category::all();
        $warehouses = Warehouse::all();

        return view('products', compact('categories', 'warehouses'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create');

        $request->validate([
            'code' => 'required|numeric|unique:products',
            'name' => 'required',
            'price' => 'required',
            'cost' => 'required',
        ]);

        $product = new Product();
        $product->create($request->all());

        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Menambahkan Produk Baru (" . $request->code . " - " . $request->name . ")",
        ]);

        return response()->json($product);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update');

        $request->validate([
            'code' => 'required|numeric',
            'name' => 'required',
            'price' => 'required',
            'cost' => 'required',
        ]);
        $product->update($request->all());

        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Mengubah Produk (" . $request->code . " - " . $request->name . ")",
        ]);

        return response()->json($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete');

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => "Menghapus Produk (" . $product->code . " - " . $product->name . ")",
        ]);

        $product->delete();

        return response()->json($product);

    }
}
