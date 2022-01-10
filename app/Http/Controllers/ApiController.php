<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\Datatables\Datatables;

class ApiController extends Controller
{
    // method-method yang berfungsi untuk mengambil data dan mengembalikannya dengan format datatables plugin.

    public function getProducts()
    {
        $products = Product::orderByDesc('id');
        $datatable = Datatables::of($products)
                    ->removeColumn('created_at')
                    ->removeColumn('updated_at')
                    ->editColumn('category', function($products) {
                        return $products->category->name;
                    })
                    ->editColumn('warehouse', function($products) {
                        return $products->warehouse->name;
                    })
                    ->addIndexcolumn()
                    ->make(true);
        return $datatable;
    }

    public function getCategories()
    {
        $categories = Category::orderByDesc('id');
        $datatable = Datatables::of($categories)->addIndexColumn()->make(true);
        return $datatable;
    }

    public function getWarehouses()
    {
        $warehouses = Warehouse::orderByDesc('id');
        $datatable = DataTables::of($warehouses)->addIndexColumn()->make(true);
        return $datatable;
    }

}
