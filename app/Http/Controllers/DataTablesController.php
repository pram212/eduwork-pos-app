<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;
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
        $purchases = Purchase::with('products')->select('purchases.*');
        $dataTable = DataTables::of($purchases)
            ->editColumn('created_at', function($purchases) {
                return formatTanggal($purchases->created_at);
            })
            ->addColumn('supplier', function($purchases) {
                return $purchases->supplier->company_name;
            })
            ->addColumn('action', function($purchases) {
                $bayarBtn = '<a href="#" onclick="app.formBayar(event, '. $purchases->id . ')" class="btn btn-xs btn-success me-2">Bayar</a>';
                $delBtn = '<a href="#" onclick="app.delete(event, '. $purchases->id .')" class="btn btn-xs btn-danger" id="hapusSale">Hapus</a>';
                $action = $bayarBtn . $delBtn;
                return $action;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        return $dataTable;
    }
}
