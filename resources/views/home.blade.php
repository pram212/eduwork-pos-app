@extends('layouts.app')
@section('title', 'Dashoard')
@section('content-title', 'Dashoard')
@section('card-header', 'Grafik')
@section('breadcrumb', 'Dashoard')
@section('sub-breadcrumb', 'Grafik')

@section('content')
    <div class="row">
        <div class="col-sm-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{rupiah($pemasukan)}}</h3>
                    <p>Pemasaukan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{rupiah($pengeluaran)}}</h3>
                    <p>Pengeluaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Rp {{rupiah($pemasukan - $pengeluaran)}}</h3>
                    <p>Rugi Laba</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$total_product}}</h3>
                    <p>Total Produk</p>
                </div>
                <div class="icon">
                    <i class="fas fa-th-large"></i>
                </div>
            </div>
        </div>
        <!-- BAR CHART -->
        <div class="col-sm-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Grafik Transaksi Tahun {{ date("Y") }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pie CHART -->
        <div class="col-sm-6">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Penjualan PerProduk</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 400px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Doughnut CHART -->
        <div class="col-sm-6">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Pembelian PerProduk</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="doughnutChart" style="min-height: 250px; height: 250px; max-height: 400px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Js::from sama dengan fungsi JSON.parse yang disediakan olrh laravel
        var dataBars = {{ Js::from($dataBars) }};
        var dataPies = {{ Js::from($dataPies) }};
        var dataDoughnuts = {{ Js::from($dataDoughnuts) }};

        // random color function
        var dynamicColors = function() {
                    var r = Math.floor(Math.random() * 200);
                    var g = Math.floor(Math.random() * 200);
                    var b = Math.floor(Math.random() * 200);
                    return "rgb(" + r + "," + g + "," + b + ")";
                };

        $(function () {
                const labels = [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',];
                const data = {
                    labels: labels,
                    datasets: [
                        {
                            label: dataBars[0]['label'],
                            backgroundColor: 'lightgray',
                            borderColor: 'rgb(255, 99, 132)',
                            data: dataBars[0]['data'],
                        },
                        {
                            label: dataBars[1]['label'],
                            backgroundColor: 'lightblue',
                            borderColor: 'rgb(255, 99, 132)',
                            data: dataBars[1]['data'],
                        }
                    ]
                };

                const config = {
                    type: 'bar',
                    data: data,
                    options: {}
                };

                const myChart = new Chart(
                    document.getElementById('barChart'),
                    config
                );

                // Bg setup for Pie Chart
                var colorPie = [];
                for (var i in dataPies['label']) {
                    colorPie.push(dynamicColors());
                }

                // Pie Chart
                const dataPie = {
                labels: dataPies['label'],
                datasets: [{
                        label: 'My First Dataset',
                        data: dataPies['data'],
                        backgroundColor: colorPie,
                        hoverOffset: 4
                    }]
                };

                const configPie = {
                    type: 'pie',
                    data: dataPie,
                };

                const myChart2 = new Chart(
                    document.getElementById('pieChart'),
                    configPie
                );


                // Bg setup for Pie Chart
                var colorDoughnut = [];
                for (var i in dataDoughnuts['label']) {
                    colorDoughnut.push(dynamicColors());
                }
                // Doughnut Chart
                const dataDoughnut = {
                labels: dataDoughnuts['label'],
                datasets: [{
                        label: 'My First Dataset',
                        data: dataDoughnuts['data'],
                        backgroundColor: colorDoughnut,
                        hoverOffset: 4
                    }]
                };

                const configDoughnut = {
                    type: 'doughnut',
                    data: dataDoughnut,
                };

                const myChart3 = new Chart(
                    document.getElementById('doughnutChart'),
                    configDoughnut
                );

        });
    </script>
@endpush
