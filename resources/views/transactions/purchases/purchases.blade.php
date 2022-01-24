@extends('layouts.app')
@section('title', 'Pembelian')
@section('content-title', 'Pembelian')
@section('card-header', 'Data Pembelian')
@section('breadcrumb', 'Pembelian')
@section('sub-breadcrumb', 'Data Pembelian')
@section('content')
    <div class="row">
        <div class="col-6">
            <a href="#" @click="create()" class="btn btn-primary btn-sm">Pembelian Baru</a>
        </div>
        <div class="col-6">
            <label for="">Filter</label>
            <select class="form-control form-control-sm" name="" id="">
                <option>Kemarin</option>
                <option>Hari Ini</option>
                <option>Bulan ini</option>
            </select>
        </div>
    </div>
    <hr>
    {{-- data content --}}
    <table class="table table-bordered table-sm text-center w-100" id="sales-table">
        <thead>
            <th>No</th>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Jatuh Tempo</th>
            <th>Status Bayar</th>
            <th>Status Penerimaan</th>
            <th>Total Tagihan</th>
            <th>Pembayaran</th>
            <th>Opsi</th>
        </thead>
    </table>
    {{-- data content end --}}


    {{-- create form --}}
    @include('transactions.purchases.create')

    @include('transactions.purchases.payment')
    {{-- @include('transactions.sales.edit') --}}

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
        var action = '{{url('purchases')}}';
        var api = '{{url('datatable/purchases')}}';
        var daftarProduk = JSON.parse('{!! $products !!}');
        var columns = [
            { data: "id", name: "id" },
            { data: "code", name: "code"},
            { data: "created_at", name: "created_at"},
            { data: "payment_deadline", name: "payment_deadline"},
            { data: "payment_status", name: "payment_status"},
            { data: "acceptance_status", name: "acceptance_status"},
            { data: "grand_total", name: "grand_total"},
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
                ongkir: 0,
                tagihan: 0,
                supplier: 0,
                deadline: '',
                pembayaran: 0,
            },
            mounted: function () {
                this.datatable();
                $('.select2').select2({ theme: 'bootstrap4', dropdownParent: $('#createModal') });
                $('.select3').select2({ theme: 'bootstrap4', dropdownParent: $('#editModal') });
                this.tahigan = parseInt(this.grandTotal) + parseInt(this.ongkir);
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
                            // isi variable data dengan data dari datatable ajax
                            _this.datas = _this.table.ajax.json().data;
                        });
                },
                // method menampilkan form create
                create() {
                    $("#createModal").modal();
                    const _this = this;
                    _this.data = {};
                    _this.orders = []
                    _this.grandTotal = 0
                    _this.pembayaran = 0
                },
                // method menyimpan penjualan baru
                store(){
                    const _this = this
                    // untuk mengisi request product_id
                    const produk = [];
                    // untuk mengisi pivot quantity
                    const quantity = [];
                    // orders otomatis terisi
                    for (let i = 0; i < this.orders.length; i++) {
                        const order = this.orders[i];
                        // tambahkan data berupa id produk ke array produk
                        produk.push(order.product_id)
                        // tambahkan data berupa quantity ke array quantity
                        quantity.push(parseInt(order.quantity))
                    }
                    // buat data yang akan dikirim ke controller
                    const data = {
                        harga: _this.grandTotal,
                        tagihan: parseInt($("#tagihan").val()),
                        supplier: $("#supplier").val(),
                        ongkir: _this.ongkir,
                        deadline: _this.deadline,
                        produk: produk,
                        quantity: quantity
                    }
                    axios.post( action, data )
                        .then(function (response) {
                            // tampilkan sweetalert
                            const message = response.data
                            // tampilkan notifikasi sukses
                            _this.notifySuccess(message);
                            // sembunyikan modal box create
                            $("#createModal").modal("hide");
                            // reload kembali table
                            _this.table.ajax.reload();
                        })
                        .catch(function (error) {
                            // console.log(error)
                            const message = error.response;
                            // tampilkan notifikasi error
                            _this.notifyError(message);
                        });
                },
                // notify success
                notifySuccess(message) {
                    Swal.fire({
                        title: "Mantap",
                        icon: "success",
                        text: message,
                    });
                },
                // notify error
                notifyError(message) {
                    if (message) {
                        var invalid = message.data.errors;
                        var html = "";
                        $.each( invalid, function (indexInArray, valueOfElement) {
                                html += `<div clas='text-danger'> ${valueOfElement}</div> <br>`;
                        });
                        Swal.fire({
                            title: "Gagal!",
                            icon: "error",
                            html: html,
                            confirmButtonText: "Ulangi",
                        });
                    }
                },
                //method menampilkan form pembayaran
                formBayar(e, id) {
                    const _this = this;
                    $("#formBayar").modal();
                    _this.getPurchase(id);
                },
                // method menyimpan pembayaran
                simpanPembayaran(e, id) {
                    const _this = this;
                    const url = '{!! url('pay/purchase') !!}' + '/' + id;
                    const pembayaran = $("#pembayaran").val();
                    axios.post(url, { pembayaran: pembayaran })
                        .then(function(response) {
                            // tampilkan sweetalert
                            const message = response.data
                            // tampilkan notifikasi sukses
                            _this.notifySuccess(message);
                            // sembunyikan modal box create
                            $("#formBayar").modal("hide");
                            // reload kembali table
                            _this.table.ajax.reload();
                        })
                        .catch(function (error) {
                            // console.log(error)
                            const message = error.response;
                            // tampilkan notifikasi error
                            _this.notifyError(message);
                        });
                },
                // method mendapatkan data lewat api
                getPurchase(id) {
                    const _this = this;
                    const url = '{!! url('get/purchase')  !!}' + '/' + id;
                    // ambil data dengan ajax
                    axios.get(url)
                        .then(function (response) {
                            console.log(response)
                            // isi data dengan response
                            _this.data = response.data;
                            // ambil data products
                            const oldOrders = _this.data.products;
                            // lakukan pengulangan terhadap products
                            for (let i = 0; i < oldOrders.length; i++) {
                                const old = oldOrders[i];
                                // isi orders dengan data products
                                _this.orders.push({
                                    product_id : old.id,
                                    code: old.code,
                                    name: old.name,
                                    price: old.price,
                                    quantity: old.pivot.quantity,
                                    total: old.price * old.pivot.quantity
                                });
                            }
                            // isi pembayaran dengan payment dari data
                            _this.pembayaran = _this.data.payment
                            // hitung ulang harga total
                            _this.hitungGrandTotal();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
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
            }
        })
    </script>
@endpush
