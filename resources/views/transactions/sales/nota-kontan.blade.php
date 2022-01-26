@extends('layouts.app')
@section('title', 'Dashoard')
@section('content-title', 'Dashoard')
@section('card-header', 'Grafik')
@section('breadcrumb', 'Dashoard')
@section('sub-breadcrumb', 'Grafik')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Nota</h5>
                    <button class="btn btn-secondary float-right" type="button" onclick="window.print()">Cetak</button>
                </div>
                <div class="card-body border rounded">
                    <div class="row">
                        <div class="col-12 text-center mb-2">
                            <p>Toko ABC <br> Cihampelas Kab. Bandung Barat</p>
                        </div>
                        <table class="table">
                            <tr>
                                <th>Tanggal</th>
                                <td>: {{$sale->created_at}}</td>
                            </tr>
                            <tr>
                                <th>Kode Transaksi</th>
                                <td>: {{$sale->code}}</td>
                            </tr>
                        </table>
                        <div class="col-12 text-center">
                            <table class="table table-sm">
                                <thead>
                                    <th>Merek</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </thead>
                                <tbody>
                                    @php
                                        $grandTotal = 0
                                    @endphp
                                    @foreach ($sale->products as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>{{ rupiah($item->price) }}</td>
                                        <td>{{$item->pivot->quantity}}</td>
                                        <td>{{rupiah($item->pivot->quantity * $item->price)}}</td>
                                        @php
                                            $grandTotal += ( $item->pivot->quantity * $item->price )
                                        @endphp
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right">Subtotal</td>
                                        <td>{{ rupiah($grandTotal) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Tunai</td>
                                        <td>{{ rupiah($sale->payment) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Kembalian</td>
                                        <td>{{ rupiah($sale->payment - $grandTotal) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">

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
            table thead th, table tbody tr td, table tfoot, body {
                color: black;
            }
        }
    </style>
@endsection

@push('script')
    <script>

    </script>
@endpush
