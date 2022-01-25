<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Activity;
use App\Models\Warehouse;
use Yajra\DataTables\DataTables;

class DataTablesController extends Controller
{
    public function getActivities()
    {
        $activities = Activity::all();
        $dataTable = DataTables::of($activities)
                    ->editColumn('created_at', function($activities) {
                        return formatTanggal($activities->created_at);
                    })
                    ->addColumn('user', function($activities) {
                        return $activities->user->name;
                    })
                    ->addIndexColumn()
                    ->toJson();

        return $dataTable;
    }

    public function getSales()
    {
        $sales = Sale::with('products')->select('sales.*');
        $dataTable = DataTables::of($sales)
            ->editColumn('created_at', function($sales) {
                return formatTanggal($sales->created_at);
            })
            ->addColumn('action', function($sales) {
                $editBtn = '<a href="#" onclick="app.edit(event, '. $sales->id .')" class="btn btn-xs btn-info" id="editSale">Edit</a>';
                $delBtn = '<a href="#" onclick="app.delete(event, '. $sales->id .')" class="btn btn-xs btn-danger mx-2" id="hapusSale">Hapus</a>';
                $action = $editBtn .= $delBtn;
                return $action;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        return $dataTable;
    }

    public function getPurchases()
    {
        $purchases = Purchase::with('products')->withSum('payments as total_amount', 'amount');
        $dataTable = DataTables::of($purchases)
            ->editColumn('created_at', function($purchases) {
                return formatTanggal($purchases->created_at);
            })
            ->addColumn('supplier', function($purchases) {
                return $purchases->supplier->company_name;
            })
            ->addColumn('reminder', function($purchases) {
                $paid = $purchases->payments->sum('amount');
                $reminder = $purchases->grand_total - $paid;
                return $reminder;
            })
            ->addColumn('action', function($purchases) {
                $deleteButton = '<a href="#" onclick="app.delete(event, '. $purchases->id .')" class="btn btn-xs btn-danger" id="hapusSale">Hapus</a>';
                return $deleteButton;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        return $dataTable;
    }

    public function getPayments()
    {
        $payments = Payment::all();
        $dataTable = DataTables::of($payments)
            ->editColumn('created_at', function($payments) {
                return formatTanggal($payments->created_at);
            })
            ->addColumn('purchase-code', function($payments) {
                return $payments->purchase->code;
            })
            ->addColumn('action', function($payments) {
                $deleteButton = '<a href="#" onclick="app.delete(event, '. $payments->id .')" class="btn btn-xs btn-danger" id="hapusSale">Hapus</a>';
                return $deleteButton;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        return $dataTable;
    }

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
    public function getSuppliers()
    {
        $suppliers = Supplier::orderByDesc('id');
        $datatable = DataTables::of($suppliers)->addIndexColumn()->make(true);
        return $datatable;
    }

    public function getUsers()
    {
        $users = User::orderByDesc('id');
        $datatable = DataTables::of($users)
                ->editColumn('created_at', function($users) {
                    return formatTanggal($users->created_at);
                })
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
