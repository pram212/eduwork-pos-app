@extends('layouts.app')
@section('title', 'Penjualan')
@section('content-title', 'Penjualan')
@section('card-header', 'Edit Penjualan')
@section('breadcrumb', 'Penjualan')
@section('sub-breadcrumb', 'Edit Penjualan')


@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach($errors->all() as $error)
            <ul>
                <li>{{ $error }}</li>
            </ul>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <script>
            $('.alert').alert()
        </script>
    @endif

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
                                    <input type="date" name="date" id="date" class="form-control form-control-sm" value="{!! old('date', optional($transaction)->date) !!}">
                                </td>
                            </tr>
                            <tr>
                                <th>Kode</th>
                                <td>
                                    <input type="text" name="voucher" id="voucher" class="form-control form-control-sm" value="{!! old('voucher', optional($transaction)->voucher) !!}">
                                </td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>
                                    <input type="text" name="description" id="description" class="form-control form-control-sm" value="{!! old('voucher', optional($transaction)->description) !!}">
                                </td>
                            </tr>
                        </table>
                    </div>
                    {{-- /. card-cody end --}}
                </div>
                {{-- /. card-end --}}
            </div>
            {{-- col-12 end --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h5 class="card-title">Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <select class="form-control form-control-sm" @change="orderBaru($event)">
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
                                        <input type="number" :name="'quantity[' + product.product_id +']'" class="form-control form-control-sm qty" :value="product.qty" @keyup="editQuantity($event)">
                                    </td>
                                    <td>@{{product.line_total}}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" @click="hapusOrder(k, product)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="text-right font-italic">
                                <tr>
                                    <td colspan="4"><b>Total Tagihan</b></td>
                                    <td class="text-center">
                                        <input type="text" class="form-control form-dontrol-sm text-center" :value="totalTagihan">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b>Pembayaran</b></td>
                                    <td class="text-center">
                                        <input type="number" name="payment" id="payment" class="form-control form-control-sm text-center" :value="transaksi.payment">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b>Kembalian</b></td>
                                    <td class="text-center">
                                        <input type="number" name="refund" id="refund" class="form-control form-control-sm text-center" :value="transaksi.refund">
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{-- /. card-body end --}}
                </div>
                {{-- /. card end --}}
            </div>
            {{-- /. col-12 end --}}
        </div>
        {{-- /. row end --}}
        <div class="row">
            <div class="col-6">
                <a href="{{ url()->previous() }}" class="">kembali</a>
            </div>
            {{-- /. col-6 end --}}
            <div class="col-6">
                <button type="submit" class="btn btn-primary float-right">Ubah</button>
            </div>
            {{-- /. col-6 end --}}
        </div>
        {{-- /. row end --}}
    </form>
    {{-- /. form end --}}
@endsection

@section('css')
    <!-- Select2 -->
    {{-- <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2/css/select2.min.css')}}"> --}}
    {{-- <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}"> --}}
    <!-- Tempusdominus Bootstrap 4 -->
    {{-- <link rel="stylesheet" href="{{asset('adminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}"> --}}
@endsection

@push('script')
    <!-- Select2 -->
    {{-- <script src="{{asset('adminLTE/plugins/select2/js/select2.full.min.js')}}"></script> --}}
    <!-- InputMask -->
    {{-- <script src="{{asset('adminLTE/plugins/moment/moment.min.js')}}"></script> --}}
    <!-- Tempusdominus Bootstrap 4 -->
    {{-- <script src="{{asset('adminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script> --}}


    <script>
        var app = new Vue({
            el: "#app",
            data: {
                produk: JSON.parse('{!! $products !!}'), // daftar produk untuk mengisi order
                transaksi: JSON.parse('{!! $transaction !!}'), // transaksi yang dikirim dari controller  untuk mengisi form edit
                totalTagihan: 0, // untuk mengisi total harga dari orders
                orders: [], // untuk memanipulasi tabel order
                pembayaran: 0,
                kembalian: 0
            },
            mounted: function () {
                this.nilaiDefault(this.transaksi.products) // isi form edit
                this.hitungSubtotal() // hitung total harga order
                this.pembayaran = this.transaksi.payment;
                this.kembalian = this.transaksi.refund;
            },
            methods: {
                // method nilai default form
                nilaiDefault(transaksi) {
                    // looping transaksi yanng dikirim dari parameter
                    for (let i = 0; i < transaksi.length; i++) {
                        // inisialisasi index transaksi
                        const element = transaksi[i];
                        // isi data orders dengan transaksi yang telah diinisialisasi
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
                // method menamahkan order baru
                orderBaru(e) {
                    // ambil id produk yang dipilih
                    var id = e.target.value - 1;
                    // ambil data produk sesuai dengan id produk
                    var selected = this.produk[id];
                    // isi data orders
                    this.orders.push({
                        product_id: selected.id,
                        code: selected.code,
                        name: selected.name,
                        price: selected.price,
                        qty: 1,
                        line_total: 0
                    });
                    // hitung harga total
                    this.hitungSubtotal()
                    // atur ulang data pembayaran dan kembalian menjadi 0
                    this.transaksi.payment = 0;
                    this.transaksi.refund = 0;
                },
                // method untuk menghapus order yang ada
                hapusOrder(index, product) {
                    // filter data orders sesuai dengan index dari parameter
                    var idx = this.orders.indexOf(product);
                    // console.log(idx, index); testing

                    if (idx > -1) {
                        this.orders.splice(idx, 1);
                    }
                    // hitung ulang harga total
                    this.hitungSubtotal();
                    // atur ulang data pembayaran dan kembalian menjadi 0
                    this.transaksi.payment = 0;
                    this.transaksi.refund = 0;
                },
                // method untuk menghitung subtotal harga masing-masing orders
                hitungSubtotal() {
                    // inisialisasi data orders
                    var daftarOrder = this.orders;
                    // atur ulang total tagihan
                    this.totalTagihan = 0;
                    // looping data orders
                    for (let i = 0; i < daftarOrder.length; i++) {
                        // ambil orders ke-n
                        const element = daftarOrder[i];
                        // ambil harga order ke-n
                        const harga = element.price;
                        // ambil quantity orders ke-n
                        const qty = element.qty;
                        // kalikan harga ke-n dengan quantity ke-n dari orders ke-n
                        const subtotal = parseInt(harga) * parseInt(qty);
                        // isi subtotal dari orders ke-n dengan subtotal
                        this.orders[i].line_total = subtotal;
                        // tambah data total tagihan dengan subtotal ke-n
                        this.totalTagihan += subtotal;
                    }
                },
                // method untuk menghandle perubahan nilai quantity
                editQuantity(event) {
                    // ambil semua elemen quantity
                    var element = $(".qty");
                    // ambil nilai quantity yang diisi
                    var nilai = event.target.value;
                    // inisialisasi element yang diubah
                    var id = Array.from(element).indexOf(event.target)
                    // ubah data orders yang sudah diinisialisasi dengan nilai
                    this.orders[id].qty = nilai;
                    // console.log(this.orders[id].qty)
                    // hitung ulang total harga
                    this.hitungSubtotal();
                    // atur ulang nilai pembayaran dan kembalian
                    this.transaksi.payment = 0;
                    this.transaksi.refund = 0;
                },
                mataUang(number) {
                    return number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                },
                // // method hitung kembalian
                hitungKembalian(e) {
                    let nilai = e.target.value;
                    let kembalian = nilai - this.totalTagihan;
                    this.transaksi.payment = nilai;
                    this.transaksi.refund = kembalian;
                }
            },

        })
    </script>
@endpush
