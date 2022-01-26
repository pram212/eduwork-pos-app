@extends('layouts.app')
@section('title', 'Penjualan')
@section('content-title', 'Penjualan')
@section('card-header', 'Kasir')
@section('breadcrumb', 'Penjualan')
@section('sub-breadcrumb', 'Kasir')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Penjualan Baru</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('sales.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-7 card">
                    <div class="card-header">
                        <div class="mb-3">
                          <label for="" class="form-label">Pilih Produk</label>
                          <select class="form-control select2" onchange="app.tambahOrder(event)">
                            <option value="">Pilih Produk</option>
                            @foreach ($products as $p)
                            <option value="{{$p->id}}">{{ $p->code }} - {{ $p->name }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="card-body">
                       <table class="table table-sm">
                           <thead class="text-center">
                               <th>Kode</th>
                               <th>Nama</th>
                               <th>Harga</th>
                               <th>Stok</th>
                               <th>Jumlah</th>
                               <th>Total</th>
                               <th>Aksi</th>
                           </thead>
                           <tbody class="text-center">
                               <tr v-for="(order, index) in orders">
                                   <input type="hidden" name="produk[]" value="1">
                                    <td>
                                        <input type="text" class="form-control form-control-sm" id="kode" :value="order.code" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" id="name" :value="order.name" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" id="harga" :value="order.price" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" id="stock" :value="order.stock" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" v-model="order.quantity" class="form-control form-control-sm" id="quantity" @keyup="hitungTotal($event, index)">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" :value="order.total" id="subtotal" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" @click="hapusOrder(index, order)"><i class="fas fa-trash"></i></button>
                                    </td>
                               </tr>
                           </tbody>
                           <tfoot>
                               <tr>
                                   <th colspan="4" class="text-right">Subtotal</th>
                                   <td colspan="2">
                                       <input type="number" class="form-control form-control-sm" name="total" :value="grandTotal" readonly>
                                   </td>
                               </tr>
                           </tfoot>
                       </table>
                    </div>
                </div>
                <div class="col-5 card">
                    <div class="card-header">
                        <p class="card-title">Detil Transaksi</p>
                    </div>
                    <div class="card-body">
                       <div class="row">
                           <div class="col-6 mb-2">
                                <label for="grandtotal">Grand Total</label>
                           </div>
                           <div class="col-6 mb-2">
                                <input type="number" class="form-control form-control-sm" name="grandtotal" :value="grandTotal" readonly>
                           </div>
                           <div class="col-6 mb-2">
                                <label for="pembayaran">Pembayaran</label>
                           </div>
                           <div class="col-6 mb-2">
                                <input type="number" class="form-control form-control-sm" name="pembayaran" v-model="pembayaran" id="pembayaran">
                           </div>
                           <div class="col-6 mb-2">
                                <label for="kembalian">Kembalian</label>
                           </div>
                           <div class="col-6 mb-2">
                                <input type="number" class="form-control form-control-sm" name="kembalian" :value="(pembayaran-grandTotal)" readonly>
                           </div>
                       </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Bayar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

@push('script')
     <!-- Select2 -->
     <script src="{{asset('adminLTe/plugins/select2/js/select2.full.min.js')}}"></script>
     <!-- Manual Script -->
     <script>
        var daftarProduk = JSON.parse('{!! $products !!}')
        var app = new Vue({
            el: '#app',
            data: {
                datas: [], // variable untuk menyimpan seluruh data produk
                data: {},
                orders: [], // variable untuk menyimpan satu produk untuk crud
                grandTotal: 0,
                pembayaran: 0,
            },
            mounted: function () {
                // select2
                $('.select2').select2({ theme: 'bootstrap4'})
            },
            methods: {
                tambahOrder(e) {
                    const id = e.target.value -1;
                    const produk = daftarProduk[id]
                    this.orders.push({
                        product_id : produk.id,
                        code: produk.code,
                        name: produk.name,
                        price: produk.price,
                        stock: produk.stock,
                        quantity: 1,
                        total: produk.price * 1
                    });
                    this.hitungGrandTotal();
                },
                hapusOrder(index, order) {
                    var idx = this.orders.indexOf(order);
                    console.log(idx, index);
                    if (idx > -1) {
                        this.orders.splice(idx, 1);
                    }
                    this.hitungGrandTotal();
                },
                hitungGrandTotal() {
                    var subtotal, total;
                    subtotal = this.orders.reduce(function (sum, order) {
                        var lineTotal = parseFloat(order.total);
                        if (!isNaN(lineTotal)) {
                            return sum + lineTotal;
                        }
                    }, 0);
                    this.grandTotal = subtotal;
                },
                hitungTotal(e, index) {
                    // dapatkan value
                    var value = e.target.value;
                    // ambil harga orders sesuai index
                    var price = this.orders[index].price;
                    // hitung ulang total orders
                    var result = parseInt(price) * parseInt(value);
                    // ubah isi total pada orders
                    this.orders[index].total = result;
                    this.hitungGrandTotal();
                },

            },
        })
    </script>
@endpush
