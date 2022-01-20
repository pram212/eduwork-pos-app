<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\Datatables\Datatables;

class ApiController extends Controller
{
    // method-method yang berfungsi untuk mengambil data dan mengembalikannya dengan format datatables plugin.

    public function getProducts()
    {
        $products = Product::all();

        $datatable = Datatables::of($products)
                    ->removeColumn('created_at')
                    ->removeColumn('updated_at')
                    ->editColumn('category', function($products) {
                        return $products->category->name;
                    })
                    ->editColumn('warehouse', function($products) {
                        return $products->warehouse->name;
                    })
                    ->addColumn('action', function($transactions) {
                        $buttons = '
                        <a href="#" onclick="app.edit(event, '. $transactions->id.')" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="#" onclick="app.delete(event, '. $transactions->id .')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                        ';
                        return $buttons;
                    })
                    ->addIndexColumn()
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

    public function getTransactions(Request $request)
    {
        if ($request->type) {
            $transactions = Transaction::where('type_id', $request->type)->orderByDesc('id')->get();
        } else {
            $transactions = Transaction::orderByDesc('id')->get();
        }

        $datatable = Datatables::of($transactions)
            ->addColumn('type_name', function($transactions) {
                return $transactions->type->name;
            })
            ->addColumn('user_name', function($transactions) {
                return $transactions->user->name;
            })
            ->addColumn('total_orders', function($transactions) {
                $quantities = [];
                foreach ($transactions->products as $product) {
                    array_push($quantities, $product->pivot->quantity);
                }
                return array_sum($quantities) . " pcs";
            })
            ->addColumn('total_payment', function($transactions) {
                $payments = [];
                foreach ($transactions->products as $product) {
                    array_push($payments, $product->price);
                }
                return "Rp " . number_format(array_sum($payments), 2, ',', '.');
            })
            ->addColumn('action', function($transactions) {
                $buttons = '
                <a href="' . url('penjualan/'. $transactions->id . '/edit') .'" class="btn btn-primary btn-sm">edit</a>
                <a href="#" onclick="app.show('. $transactions->id .')" class="btn btn-info btn-sm">detil</a>
                <a href="#" onclick="app.destroy('. $transactions->id .')" class="btn btn-danger btn-sm">
                    hapus
                </a>
                ';
                return $buttons;
            })
            ->editColumn('created_at', function($transactions) {
                return date('d/m/Y - H:i', strtotime($transactions->created_at));
            })
            ->removeColumn(['updated_at'])
            ->addIndexColumn()
            ->make(true);

        return $datatable;
    }

    public function getProduct($id)
    {
        $product = Product::find($id);
        return $product->toArray();
    }

}
