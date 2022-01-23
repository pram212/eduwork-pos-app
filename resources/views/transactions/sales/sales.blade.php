@extends('layouts.app')
@section('title', 'Penjualan')
@section('content-title', 'Penjualan')
@section('card-header', 'Data Penjualan')
@section('breadcrumb', 'Penjualan')
@section('sub-breadcrumb', 'Data Penjualan')
@section('content')
    <div class="row">
        <div class="col-sm-2">
            <a href="#" @click="create()" class="btn btn-primary btn-sm">Buka Kasir</a>
        </div>
    </div>
    <hr>
    {{-- data content --}}
    <table class="table table-bordered table-sm text-center w-100" id="sales-table">
        <thead>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode</th>
            <th>Total Harga</th>
            <th>Pembayaran</th>
            <th width="10%">Opsi</th>
        </thead>
    </table>
    {{-- data content end --}}


    {{-- create form --}}
    @include('transactions.sales.create')

    {{-- edit form --}}
    @include('transactions.sales.edit')

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

@push('script')
    {{-- datatables script --}}
    <script src="{{asset('adminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('adminLTe/plugins/select2/js/select2.full.min.js')}}"></script>

    {{-- sweetalert CDN --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var action = '{{url('sales')}}';
        var api = '{{url('datatable/sales')}}';
        var daftarProduk = JSON.parse('{!! $products !!}')
        var columns = [
            { data: "id", name: "id" },
            { data: "created_at", name: "created_at"},
            { data: "code", name: "code"},
            { data: "total_price", name: "total_price"},
            { data: "payment", name: "payment"},
            { data: "action", name: "action", orderable: false, searchable:false}
        ];

        var app = new Vue({
            el: '#app',
            data: {
                datas: [], // variable untuk menyimpan seluruh data produk
                data: {},
                orders: [], // variable untuk menyimpan satu produk untuk crud
                action,
                api,
                grandTotal: 0,
                pembayaran: 0,
                print: false
            },
            mounted: function () {
                this.datatable();
                $('.select2').select2({ theme: 'bootstrap4', dropdownParent: $('#createModal') })
                $('.select3').select2({ theme: 'bootstrap4', dropdownParent: $('#editModal') })
            },
            methods: {
                datatable() {
                    const _this = this;
                    _this.table = $("#sales-table")
                        .DataTable({
                            processing: true,
                            responsive: true,
                            serverSide: true,
                            ajax: _this.api,
                            columns,
                        })
                        .on("xhr", function () {
                            _this.datas = _this.table.ajax.json().data; // isi variable data dengan data dari datatable ajax
                        });
                },
                create() {
                    this.data = {}; // kosongkan variable data
                    $("#createModal").modal(); // tampilkan modal
                    this.orders = []
                    this.grandTotal = 0
                },
                store(e) {
                    const _this = this
                    const produk = [];
                    const quantity = [];
                    for (let i = 0; i < this.orders.length; i++) {
                        const order = this.orders[i];
                        produk.push(order.product_id)
                        quantity.push(parseInt(order.quantity))
                    }
                    const data = {
                        total: this.grandTotal,
                        pembayaran: this.pembayaran,
                        produk: produk,
                        quantity: quantity
                    }
                    if (data.pembayaran < data.total) {
                        Swal.fire({
                            title: "OOps",
                            icon: "error",
                            text: "Pembayaran harus lebih atau sama dengan total harga"
                        })
                    } else {
                        axios
                            .post( action, data )
                            .then(function (response) {
                                console.log(response);
                                Swal.fire({
                                    title: "Mantap",
                                    icon: "success",
                                    text: response.data
                                })
                                // $("#createModal").modal("hide");
                                _this.table.ajax.reload();
                                _this.print = true
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }

                },
                edit(event, id) {
                    const _this = this;
                    $("#editModal").modal();
                    _this.orders = [];
                    _this.data = _this.datas[id]
                    console.log(_this.data.id)
                    var oldOrders = _this.data.products;
                    for (let i = 0; i < oldOrders.length; i++) {
                        const old = oldOrders[i];
                        _this.orders.push({
                            product_id : old.id,
                            code: old.code,
                            name: old.name,
                            price: old.price,
                            quantity: old.pivot.quantity,
                            total: old.price * 1
                        });
                    }
                    _this.pembayaran = _this.data.payment
                    _this.hitungGrandTotal();
                },
                update(e, id) {
                    console.log(id)
                    const _this = this
                    const produk = [];
                    const quantity = [];
                    for (let i = 0; i < this.orders.length; i++) {
                        const order = this.orders[i];
                        produk.push(order.product_id)
                        quantity.push(parseInt(order.quantity))
                    }
                    const data = {
                        total: this.grandTotal,
                        pembayaran: this.pembayaran,
                        produk: produk,
                        quantity: quantity
                    }
                    if (data.pembayaran < data.total) {
                        Swal.fire({
                            title: "OOps",
                            icon: "error",
                            text: "Pembayaran harus lebih atau sama dengan total harga"
                        })
                    } else {
                        axios
                            .put( action + id, data )
                            .then(function (response) {
                                console.log(response);
                                Swal.fire({
                                    title: "Mantap",
                                    icon: "success",
                                    text: response.data
                                })
                                // $("#createModal").modal("hide");
                                _this.table.ajax.reload();
                                _this.print = true
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }
                },
                delete(event, id) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.delete(action + '/' + id);
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            this.table.ajax.reload();
                        }
                    });
                },
                tambahOrder(e) {
                    const id = e.target.value;
                    const produk = daftarProduk[id]
                    this.orders.push({
                        product_id : produk.id,
                        code: produk.code,
                        name: produk.name,
                        price: produk.price,
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
