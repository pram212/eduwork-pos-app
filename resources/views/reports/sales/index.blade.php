@extends('layouts.app')
@section('title', 'Laporan')
@section('content-title', 'Laporan')
@section('card-header', 'Laporan Penjualan')
@section('breadcrumb', 'Laporan')
@section('sub-breadcrumb', 'Laporan Penjualan')

@section('content')
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
                    <button class="btn btn-sm btn-secondary" id="cetak" type="button">Cetak</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="text-center" id="judul">
            <h4>Laporan Penjualan</h4>
        </div>
        <hr>
        <table class="table table-sm">
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

           // event click cetak
           $("#cetak").click(function (e) {
               e.preventDefault();
               window.print();
           });
        });
    </script>
@endpush
