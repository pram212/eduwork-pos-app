@extends('layouts.app')
@section('title', 'Tes Penjualan')
@section('content-title', 'Tes Penjualan')
@section('card-header', 'Data Tes Penjualan')
@section('breadcrumb', 'Tes Penjualan')
@section('sub-breadcrumb', 'Data Tes Penjualan')
@section('content')
    <div class="row">
        <div class="col-6">
            <a href="#" @click.prevent="create()" class="btn btn-primary btn-sm">Buka Kasir</a>
        </div>
        <div class="col-6">
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

    {{-- edit form --}}
    @include('transactions.sales.form-test')

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('adminLTe/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{asset('adminLTe/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('adminLTe/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <style>
        .modal-footer {
            text-align: center;
        }
    </style>
@endsection

@push('script')
    {{-- datatables script --}}
    <script src="{{asset('adminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('adminLTe/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('adminLTe/plugins/moment/moment.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('adminLTe/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{asset('adminLTe/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>

    {{-- sweetalert CDN --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // url action form
        var action = '{{url('sales')}}';
        // url datatable
        var api = '{{url('datatable/sales')}}';
        // template kolom datatable
        var columns = [
            { data: "DT_RowIndex", name: "DT_RowIndex", orderable: true},
            { data: "created_at", name: "created_at"},
            { data: "code", name: "code"},
            { data: "total_price", name: "total_price"},
            { data: "payment", name: "payment"},
            { data: "action", name: "action", orderable: false, searchable:false}
        ];

        // vue js script
        var app = new Vue({
            el: '#app',
            data: {
                datas: [],
                data: {},
                orders: [],
                action: action,
                api : api,
                grandTotal: 0,
                pembayaran: 0,
                method: false,
                btnPrint: false,
                products : {}
            },
            mounted: function () {
                // datatable ajax
                this.datatable();
                // select2
                $('.select2').select2({ theme: 'bootstrap4', dropdownParent: $('#formModal'), placeholder: 'Pilih Produk'})
                //Date range picker
                $('#reservation').daterangepicker({ dateFormat: 'DD-MM-YYYY' })
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
                            _this.datas = _this.table.ajax.json().data;
                        });
                },
                create() {
                    this.getproducts()
                    this.data = {};
                    this.orders = [];
                    this.method = false;
                    $("#formModal").modal();
                    $(".modal-title").text("Penjualan Baru");
                },
                edit(e, id) {
                    this.getproducts()
                    const _this = this
                    _this.orders = [];
                    _this.method = true;
                    const url = action + '/' + id + '/' + 'edit';
                    axios.get(url)
                        .then(function (response) {
                            // handle success
                            _this.data = response.data
                            // console.log(_this.data);
                            const oldOrders = _this.data.products;
                            // lakukan pengulangan terhadap products
                            for (let i = 0; i < oldOrders.length; i++) {
                                const oldProduct = oldOrders[i];
                                // isi orders dengan data products
                                _this.orders.push({
                                    product_id : oldProduct.id,
                                    code: oldProduct.code,
                                    name: oldProduct.name,
                                    price: oldProduct.price,
                                    stock: oldProduct.stock,
                                    quantity: oldProduct.pivot.quantity,
                                    total: oldProduct.price * oldProduct.pivot.quantity
                                });
                            }
                            // isi pembayaran dengan payment dari data
                            _this.pembayaran = _this.data.payment
                            // hitung ulang harga total
                            _this.hitungGrandTotal();
                        });
                    $(".modal-title").text("Edit Penjualan");
                    $("#formModal").modal();

                },
                delete(id) {
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
                save(event, id) {
                    event.preventDefault();
                    const _this = this;
                    id -1;
                    var url = !_this.method ? _this.action : _this.action + "/" + id
                    this.message = !this.method
                        ? "Transaksi Berhasil disimpan"
                        : "Transaksi berhasil diubah";
                    axios
                        .post(url, new FormData($(event.target)[0]))
                        .then((response) => {
                            _this.table.ajax.reload();
                            Swal.fire({
                                title: 'Mantap',
                                icon: 'success',
                                text: this.message
                            });
                            _this.action = action;
                            _this.data = response.data
                            _this.getproducts()
                            $('.select2').val(null).trigger('change');
                        })
                        .catch(function (error) {
                            console.log(error)
                            if (error.response) {
                                var message_error = error.response.data.errors;
                                var error_element = "";
                                $.each(
                                    message_error,
                                    function (indexInArray, valueOfElement) {
                                        error_element += `<div clas='text-danger'> ${valueOfElement}</div> <br>`;
                                    }
                                );

                                Swal.fire({
                                    title: "Gagal!",
                                    icon: "error",
                                    html: error_element,
                                    confirmButtonText: "Ulangi",
                                });
                            }
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
                tambahOrder(e) {
                    var index = e.target.value;
                    var product = this.products[index]
                    this.orders.push({
                        product_id : product.id,
                        code: product.code,
                        name: product.name,
                        price: product.price,
                        stock: product.stock,
                        quantity: 1,
                        total: product.price * 1
                    });
                    this.hitungGrandTotal();
                },
                getproducts() {
                    const _this = this
                    url = '{!! url('getproducts') !!}';
                    axios
                        .get(url)
                        .then(function(response) {
                            _this.products = response.data;
                        })
                },
                hapusOrder(index, order) {
                    var idx = this.orders.indexOf(order);
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
                mataUang(number) {
                    var rupiah = (number).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    return rupiah;
                }
            },
        })
    </script>
@endpush
