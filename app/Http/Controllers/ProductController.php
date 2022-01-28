<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $this->authorize('lihat produk');

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
        $this->authorize('tambah produk');

        $request->validate([
            'code' => 'required|numeric|unique:products',
            'name' => 'required',
            'price' => 'required',
            'cost' => 'required',
        ]);

        $product = new Product();

        $product->create($request->all());

        recordAction("Menambahkan Produk Baru (" . $request->code . " - " . $request->name . ")");

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
        $this->authorize('edit produk');

        $request->validate([
            'code' => 'required|numeric',
            'name' => 'required',
            'price' => 'required',
            'cost' => 'required',
        ]);

        $product->update( $request->all() );

        recordAction("Mengubah Produk (" . $request->code . " - " . $request->name . ")");

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
        $this->authorize('hapus produk');

        recordAction("Menghapus Produk (" . $product->code . " - " . $product->name . ")");

        $product->delete();

        return response()->json($product);

    }
}
