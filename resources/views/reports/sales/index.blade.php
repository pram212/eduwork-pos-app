@extends('layouts.app')
@section('title', 'Laporan')
@section('content-title', 'Laporan')
@section('card-header', 'Laporan Penjualan')
@section('breadcrumb', 'Laporan')
@section('sub-breadcrumb', 'Laporan Penjualan')

@section('content')

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Penjualan</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Produk</a>
    </li>
</ul>
<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

        <div class="card">
            <div class="card-header">
                <form action="{{url('get/sales/all')}}" method="Get" id="formLaporan">
                    <div class="row">
                        <div class="col-5">
                            <label for="">Tanggal Mulai</label>
                            <input type="date" class="form-control form-conrol-sm" name="tanggal_awal" id="tanggal_awal" required>
                        </div>
                        <div class="col-5">
                            <label for="">Tanggal Akhir</label>
                            <input type="date" class="form-control form-conrol-sm" name="tanggal_akhir" id="tanggal_akhir" required>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-sm btn-light d-block mb-2" type="submit">Tampilkan</button>
                            <button class="btn btn-sm btn-secondary cetak" type="button">Cetak</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="text-center" id="judul">
                    <h4>Laporan Penjualan</h4>
                </div>
                <hr>
                <table class="table table-sm text-center">
                    <thead>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Total Harga</th>
                        <th>Pembayaran</th>
                        <th>Kembalian</th>
                        <th>Total Barang</th>
                        <th>Kasir</th>
                    </thead>
                    <tbody id="semua">

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-sm btn-secondary cetak" type="button">Cetak</button>
            </div>
            <div class="card-body">
                <div class="text-center" id="judul">
                    <h4>Laporan Penjualan Produk</h4>
                </div>
                <hr>
                <table class="table table-sm text-center">
                    <thead>
                        <th>Kode</th>
                        <th>Merek</th>
                        <th>Harga Jual</th>
                        <th>Terjual</th>
                        <th>Total Penjualan</th>
                    </thead>
                    <tbody id="lap-perproduk">
                        @foreach ($lapPerProduk as $lap)
                            <tr>
                                <td>{{$lap->code}}</td>
                                <td>{{$lap->name}}</td>
                                <td>{{$lap->price}}</td>
                                <td>{{$lap->terjual}}</td>
                                <td>{{$lap->penjualan}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection

@section('css')
    <style>
        @media print {
        /* styling goes here */
            .card-header, .nav-item, footer {
                display: none;
            }
            table thead th, table tbody tr td, div#judul {
                color: black;
            }
        }
    </style>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
        // filter semua laporan pertanggal
           $("#formLaporan").submit(function (e) {
               e.preventDefault();
               $(".result").remove();
                $.ajax({
                    type: "get",
                    url: "{!! url("get/report/all") !!}",
                    data: {
                        tanggal_awal: $("#tanggal_awal").val(),
                        tanggal_akhir: $("#tanggal_akhir").val(),
                    },
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response)
                        if (response.length === 0 ) {
                            $("#semua").append(`
                                <tr class="result text-center">
                                    <td colspan="7">Tidak Ditemukan</td>
                                </tr>
                            `);
                        } else {
                            $("#judul").append(`
                                <p class="result">
                                    ${ $("#tanggal_awal").val() } s/d ${ $("#tanggal_akhir").val() }
                                </p>
                            `);
                            for (let i = 0; i < response.length; i++) {
                                const element = response[i];
                                $("#semua").append(`
                                    <tr class="result">
                                        <td>${element.created_at}</td>
                                        <td>${element.code}</td>
                                        <td>${element.total_price}</td>
                                        <td>${element.payment}</td>
                                        <td>${element.payment - element.total_price}</td>
                                        <td>${element.products_count}</td>
                                        <td>Kasir</td>
                                    </tr>
                                `);
                            }
                        }
                    }
                });
           }); // form submit end


           // filter laporan perproduk
           $("#formLaporanPerProduk").submit(function (e) {
               e.preventDefault();
               $(".result").remove();
               $.ajax({
                    type: "get",
                    url: "{!! url("get/report/product") !!}",
                    data: {
                        produk: $("#produk").val(),
                    },
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response)
                        if (response.length === 0 ) {
                            $("#lap-perproduk").append(`
                                <tr class="result text-center">
                                    <td colspan="7">Tidak Ditemukan</td>
                                </tr>
                            `);
                        } else {
                            $("#judul").append(`
                                <p class="result">
                                    ${ $("#tanggal_awal").val() } s/d ${ $("#tanggal_akhir").val() }
                                </p>
                            `);
                            for (let i = 0; i < response.length; i++) {
                                const element = response[i];
                                $("#lap-perproduk").append(`
                                    <tr class="result">
                                        <td>${element.code}</td>
                                        <td>${element.name}</td>
                                        <td>${element.price}</td>
                                        <td>${element.terjual}</td>
                                        <td>${element.penjualan}</td>
                                    </tr>
                                `);
                            }
                        }
                    }
                });

           });
           // event click cetak
           $(".cetak").click(function (e) {
               e.preventDefault();
               window.print();
           });
        });
    </script>
@endpush
