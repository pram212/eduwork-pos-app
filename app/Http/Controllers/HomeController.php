<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Melihat Dashboard Grafik",
        ]);

        // calculating widget data
        # datas
        $now = date("m");
        $sales = Sale::whereMonth('created_at', $now)->get();
        $paymentPurchases = Payment::whereMonth('created_at', $now)->get();
         # render
        $pemasukan = $sales->sum('total_price');
        $pengeluaran = $paymentPurchases->sum('amount');
        $total_product = Product::count();

        $dataPies = $this->pieChart();

        $dataBars = $this->barChart();

        $dataDoughnuts = $this->doughnutChart();

        return view('home', compact('pemasukan', 'pengeluaran','total_product', 'dataBars', 'dataPies', 'dataDoughnuts'));
    }

    public function barChart()
    {
        $barChartlabel = ['penjualan', 'pembelian'];
        $dataBars = [];
        foreach ($barChartlabel as $key => $value) {
            $dataBars[$key]['label'] = $barChartlabel[$key];
            $dataBars[$key]['backgroundColor'] = $key == 0 ? "lightgray" : "lightblue" ;
            $dataSets = [];
            for ($i=1; $i < 12; $i++) {
                if ($key == 0) {
                    array_push($dataSets, Sale::whereMonth('created_at', $i)->sum('payment'));
                } else {
                    # code...
                    array_push($dataSets, Payment::whereMonth('created_at', $i)->sum('amount'));
                }

            }
            $dataBars[$key]['data'] = $dataSets;
        }

        return $dataBars;
    }

    public function pieChart()
    {
        $produk = Product::has('sales')->get();
        $pieChartLabel = [];
        $pieChartData = [];
        $dataPies = [];

        foreach ($produk as $key => $value) {
            array_push($pieChartLabel, $value->name);
            array_push($pieChartData, $value->sales->sum('pivot.quantity') );
        }

        $dataPies['label'] = $pieChartLabel;
        $dataPies['data'] = $pieChartData;

        return $dataPies;
    }

    public function doughnutChart()
    {
        $produk = Product::has('purchases')->get();
        $doughnutChartLabel = [];
        $doughnutChartData = [];
        $dataDoughnut = [];

        foreach ($produk as $key => $value) {
            array_push($doughnutChartLabel, $value->name);
            array_push($doughnutChartData, $value->sales->sum('pivot.quantity') );
        }

        $dataDoughnut['label'] = $doughnutChartLabel;
        $dataDoughnut['data'] = $doughnutChartData;

        return $dataDoughnut;
    }
}
