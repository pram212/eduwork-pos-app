<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Activity;
use App\Models\Product;
use App\Models\Payment;
use App\Models\User;
use App\Models\Sale;

class DataTablesController extends Controller
{
    public function getActivities()
    {
        $activities = Activity::all();

        $dataTable =
            DataTables::of($activities)
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
        $sales = Sale::with('products')->orderBy('id', 'desc')->get();

        $dataTable =
                DataTables::of($sales)
                ->editColumn('created_at', function($sales) {
                    return formatTanggal($sales->created_at);
                })
                ->addColumn('action', function($sales) {
                    $editBtn = '<a href="#" onclick="app.edit(event, '. $sales->id .')" class="btn btn-xs btn-info" id="editSale">Edit</a>';
                    $delBtn = '<a href="#" onclick="app.delete('. $sales->id .')" class="btn btn-xs btn-danger mx-2" id="hapusSale">Hapus</a>';
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

        $dataTable =
            DataTables::of($purchases)
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
        $dataTable =
            DataTables::of($payments)
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
        $datatable =
            Datatables::of($products)
                ->editColumn('category', function($products) {
                    return $products->category->name;
                })
                ->editColumn('warehouse', function($products) {
                    return $products->warehouse->name;
                })
                ->addColumn('action', function($products) {
                    $user = Auth::user();
                    $btnUpdate = ($user->can('edit produk'))
                        ? '<a href="#" onclick="app.edit(event, '. $products->id.')" class="btn btn-primary btn-xs mx-1">Edit</a>'
                        : '<a href="#" class="btn btn-primary btn-xs mx-1">can\'t edit</a>';
                    $btnDelete = ($user->can('hapus produk'))
                        ? '<a href="#" onclick="app.destroy(event, '. $products->id .')" class="btn btn-danger btn-xs mx-1">Hapus</a>'
                        : '<a href="#" class="btn btn-danger btn-xs mx-1">can\'t delete</a>';

                    return $btnUpdate . $btnDelete;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);

        return $datatable;

    }

    public function getCategories()
    {
        $categories = Category::all();

        $datatable =
            Datatables::of($categories)
                ->addColumn('action', function($categories) {
                    $user = Auth::user();
                    $btnUpdate = ($user->can('edit kategori'))
                        ? '<a href="#" onclick="app.edit(event, '. $categories->id.')" class="btn btn-primary btn-xs mx-1">Edit</a>'
                        : '<a href="#" class="btn btn-primary btn-xs mx-1">can\'t edit</a>';
                    $btnDelete = ($user->can('hapus kategori'))
                        ? '<a href="#" onclick="app.destroy(event, '. $categories->id .')" class="btn btn-danger btn-xs mx-1">Hapus</a>'
                        : '<a href="#" class="btn btn-danger btn-xs mx-1">can\'t delete</a>';

                    return $btnUpdate . $btnDelete;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);

        return $datatable;

    }

    public function getWarehouses()
    {
        $warehouses = Warehouse::all();
        $datatable =
            DataTables::of($warehouses)
                ->addColumn('action', function($warehouses) {
                    $user = Auth::user();
                    $btnUpdate = ($user->can('edit gudang'))
                        ? '<a href="#" onclick="app.edit(event, '. $warehouses->id.')" class="btn btn-primary btn-xs mx-1">Edit</a>'
                        : '<a href="#" class="btn btn-primary btn-xs mx-1">can\'t edit</a>';
                    $btnDelete = ($user->can('hapus gudang'))
                        ? '<a href="#" onclick="app.destroy(event, '. $warehouses->id .')" class="btn btn-danger btn-xs mx-1">Hapus</a>'
                        : '<a href="#" class="btn btn-danger btn-xs mx-1">can\'t delete</a>';

                    return $btnUpdate . $btnDelete;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);

        return $datatable;

    }
    public function getSuppliers()
    {
        $suppliers = Supplier::all();
        $datatable =
            DataTables::of($suppliers)
                ->addColumn('action', function($suppliers) {
                    $user = Auth::user();
                    $btnUpdate = ($user->can('edit supplier'))
                        ? '<a href="#" onclick="app.edit(event, '. $suppliers->id.')" class="btn btn-primary btn-xs mx-1">Edit</a>'
                        : '<a href="#" class="btn btn-primary btn-xs mx-1">can\'t edit</a>';
                    $btnDelete = ($user->can('hapus supplier'))
                        ? '<a href="#" onclick="app.destroy(event, '. $suppliers->id .')" class="btn btn-danger btn-xs mx-1">Hapus</a>'
                        : '<a href="#" class="btn btn-danger btn-xs mx-1">can\'t delete</a>';

                    return $btnUpdate . $btnDelete;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);

        return $datatable;
    }

    public function getUsers()
    {
        $users = User::all();
        $datatable = DataTables::of($users)
                ->editColumn('created_at', function($users) {
                    return formatTanggal($users->created_at);
                })
                ->addColumn('action', function($users) {
                    $buttons = '
                    <a href="#" onclick="app.show(event, '. ($users->id - 1) .')" class="btn btn-primary btn-xs">Lihat</a>
                    <a href="#" onclick="app.destroy(event, '. $users->id.')" class="btn btn-danger btn-xs">Hapus</a>
                    ';

                    return $buttons;
                })
                ->addColumn('roles', function($users) {
                    $roles = "";
                    foreach ($users->roles as $role) {
                        $roles .= $role->name;
                    }
                    return $roles;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        return $datatable;
    }

    public function getProduct($id)
    {
        $product = Product::find($id);
        return $product->toArray();
    }

    public function getRoles()
    {
        $roles = Role::all();
        $datatable = Datatables::of($roles)
            ->addColumn('action', function($roles) {
                $buttons = '
                <a href="#" onclick="app.update(event, '. $roles->id.')" class="btn btn-primary btn-xs">Edit</a>
                <a href="#" onclick="app.destroy(event, '. $roles->id .')" class="btn btn-danger btn-xs">Hapus</a>
                <a href="#" onclick="app.editPermission(event, '. $roles->id .')" class="btn btn-success btn-xs">Permission</a>
                ';
                return $buttons;
            })
            ->addColumn('users', function($roles) {
                $users = "";
                foreach ($roles->users as $user) {
                    $users .= $user->name . ", ";
                }
                return $users;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);

        return $datatable;
    }
}
