@extends('layouts.app')
@section('title', 'Laporan')
@section('content-title', 'Laporan')
@section('card-header', 'Laporan Pembelian')
@section('breadcrumb', 'Laporan')
@section('sub-breadcrumb', 'Laporan Pembelian')

@section('content')

<div class="card">
    <div class="card-header">
        <form action="{{url('report/purchases/filter')}}" method="Get" id="formLaporan">
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
            <h4>Laporan Pembelian</h4>
        </div>
        <hr>
        <table class="table table-sm text-center">
            <thead>
                <th>Kode</th>
                <th>Tanggal Beli</th>
                <th>Deadline</th>
                <th>Total Harga</th>
                <th>Terbayar</th>
                <th>Sisa</th>
                <th>PIC</th>
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
        // filter semua laporan pertanggal
           $("#formLaporan").submit(function (e) {
               e.preventDefault();
               $(".result").remove();
                $.ajax({
                    type: "get",
                    url: "{!! url("report/purchases/filter") !!}",
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
                                        <td>${element.code}</td>
                                        <td>${element.created_at}</td>
                                        <td>${element.payment_deadline}</td>
                                        <td>${element.grand_total}</td>
                                        <td>${element.paid}</td>
                                        <td>${element.grand_total - element.paid}</td>
                                        <td>Kasir</td>
                                    </tr>
                                `);
                            }
                        }
                    }
                });
           }); // form submit end

           // event click cetak
           $(".cetak").click(function (e) {
               e.preventDefault();
               window.print();
           });
        });
    </script>
@endpush
