@extends('layouts.app')
@section('title', 'Penjualan')
@section('content-title', 'Penjualan')
@section('card-header', 'Edit Penjualan')
@section('breadcrumb', 'Penjualan')
@section('sub-breadcrumb', 'Edit Penjualan')


@section('content')
<form action="{{ url('penjualan/'. $transaction->id ) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary">
                    <h5 class="card-title">Detil Transaksi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Tanggal</th>
                            <td>
                                <input type="date" name="date" id="date" class="form-control form-control-sm" value="{{$transaction->date}}">
                            </td>
                        </tr>
                        <tr>
                            <th>Kode</th>
                            <td>
                                <input type="text" name="voucher" id="voucher" class="form-control form-control-sm" value="{{ $transaction->voucher }}">
                            </td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>
                                <input type="text" name="description" id="description" class="form-control form-control-sm" value="{{ $transaction->description }}">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        {{-- /. col-6 --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary">
                    <h5 class="card-title">Order</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <select class="form-control form-control-sm" v-on:change="orderBaru($event)">
                            <option value="" selected >Tambah Produk</option>
                            @foreach ($products as $product)
                                <option value="{{$product->id}}">{{$product->code}} | {{$product->name}} ( Rp {{$product->price}} )</option>
                            @endforeach
                        </select>
                    </div>
                    <table class="table table-sm" width="100%">
                        <thead class="text-center">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga Jual</th>
                                <th width="15%">Jumlah</th>
                                <th width="20%">SubTotal</th>
                                <th width="15%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="(product, k) in orders" :key="k">
                                <input type="hidden" name="product_id[]" :value="product.product_id">
                                <td>
                                    @{{product.code}}
                                </td>
                                <td>@{{product.name}}</td>
                                <td>@{{product.price}}</td>
                                <td>
                                    <input type="number" :name="'quantity[' + product.product_id +']'" class="form-control form-control-sm qty" :value="product.qty" @keyup="updateSubtotal($event)">
                                </td>
                                <td>@{{product.line_total}}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger" v-on:click="hapusOrder(k, product)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="text-right font-italic">
                            <tr>
                                <td colspan="4">
                                    <b>Total Tagihan</b>
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control form-dontrol-sm text-center" :value="grandTotal">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <b>Pembayaran</b>
                                </td>
                                <td class="text-center">
                                    <input type="number" name="payment" id="payment" class="form-control form-control-sm text-center" :value="pembayaran">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <b>Kembalian</b>
                                </td>
                                <td class="text-center">
                                    <input type="number" name="refund" id="refund" class="form-control form-control-sm text-center" :value="kembalian">
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        {{-- /. col-12 --}}
    </div>
    <div class="row">
        <div class="col-6">
            <a href="{{ url()->previous() }}" class="">kembali</a>
        </div>
        <div class="col-6">
            <button type="submit" class="btn btn-primary float-right">Ubah</button>
        </div>
    </div>
</form>
@endsection

@section('css')
 <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@endsection

@push('script')
<!-- Select2 -->
    <script src="{{asset('adminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('adminLTE/plugins/moment/moment.min.js')}}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{asset('adminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>

    {{-- sweetalert CDN --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var app = new Vue({
                    el: "#app",
                    data: {
                        produk: JSON.parse('{!! $products !!}'),
                        penjualan: JSON.parse('{!! $transaction->products !!}'),
                        grandTotal: 0,
                        orders: [],
                        pembayaran: JSON.parse('{!! $transaction->payment !!}'),
                        kembalian: JSON.parse('{!! $transaction->refund !!}'),
                    },
                    mounted: function () {
                        this.loadOrder(this.penjualan)
                        this.hitungSubtotal()
                    },
                    methods: {
                        loadOrder(obj) {
                            for (let i = 0; i < obj.length; i++) {
                                const element = obj[i];
                                this.orders.push({
                                    product_id: element.id,
                                    code: element.code,
                                    name: element.name,
                                    price: element.price,
                                    qty: element.pivot.quantity,
                                    line_total: 0
                                });
                            }
                        },
                        orderBaru(e) {
                            // ambil value sebagai id
                            var id = e.target.value - 1;
                            // filter produk berdasarkan id
                            var selected = this.produk[id];

                            this.orders.push({
                                product_id: selected.id,
                                code: selected.code,
                                name: selected.name,
                                price: selected.price,
                                qty: 1,
                                line_total: 0
                            });

                            this.hitungSubtotal()
                        },
                        hapusOrder(index, product) {
                            var idx = this.orders.indexOf(product);
                            console.log(idx, index);
                            if (idx > -1) {
                                this.orders.splice(idx, 1);
                            }
                            this.hitungSubtotal()
                        },
                        hitungSubtotal() {
                            var semuaproduk = this.orders;
                            this.grandTotal = 0;
                            for (let p = 0; p < semuaproduk.length; p++) {
                                const element = semuaproduk[p];
                                const harga = element.price;
                                const qty = element.qty;
                                const subtotal = parseInt(harga) * parseInt(qty);
                                this.orders[p].line_total = subtotal;
                                this.grandTotal += subtotal;
                            }
                        },
                        hitungTotal(total, num) {
                            return total + num;
                        },
                        updateSubtotal(event) {
                            var element = $(".qty");
                            var nilai = event.target.value;
                            var id = Array.from(element).indexOf(event.target)
                            this.orders[id].qty = nilai;
                            console.log(this.orders[id].qty)
                            this.hitungSubtotal()
                        },
                    },
                    computed: {

                    },
                })
    </script>
@endpush
