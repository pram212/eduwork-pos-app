@extends('layouts.app')
@section('title', 'Penjualan')
@section('content-title', 'Penjualan')
@section('card-header', 'Riwayat Penjualan')
@section('breadcrumb', 'Penjualan')
@section('sub-breadcrumb', 'Riwayat Penjualan')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Mantap!</strong> {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <script>
           $('.alert').alert()
        </script>
    @endif

    <div class="row mb-3">
        <div class="col">
            <a href="#" v-on:click="create()" class="btn btn-primary">Penjualan Baru</a>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table table-bordered text-dark text-center table-sm table-striped" id="table">
                <thead class="bg-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Voucher</th>
                        <th>Jumlah Produk</th>
                        <th>Total Penjualan</th>
                        <th>Kasir</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- create Box -->
    @include('transactions.penjualan.create')
    <!-- /. create Box -->

    <!-- detail box -->
    @include('transactions.penjualan.show')
    <!-- /. detail box -->

    <!-- checkout Box -->
    @include('transactions.penjualan.checkout')
    <!-- /. checkout Box -->

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@endsection

@push('script')
    {{-- datatables script --}}
    <script src="{{asset('adminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('adminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('adminLTE/plugins/moment/moment.min.js')}}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{asset('adminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>

    {{-- sweetalert CDN --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script>
        var action = '{{url('penjualan')}}';
        var api = '{{ url('api/transactions') }}';
        var columns = [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'voucher', name: 'voucher' },
                        { data: 'total_orders', name: 'total_orders' },
                        { data: 'total_payment', name: 'total_payment' },
                        { data: 'user_name', name: 'user_name' },
                        { data: 'action', name: 'action' },
                    ]
         var app = new Vue({
            el: '#app',
            data: {
                // datatable
                datas: [],
                data: [],
                api: api,
                columns: columns,
                // crud
                products : JSON.parse('{!! $products !!}'),
                // invoice
                invoice: [],
                product_id:{},
                order: {},
                totalHarga: 0,
                pembayaran: 0,
                kembalian: 0,
                transaction: 0,
                editData: {
                    date: '',
                },
                tanggal : '2021-01-14'
            },
            //data end
            mounted: function() {
                this.index();
                // inisial date picker
                $('#reservationdate').datetimepicker({ format: 'YYYY-MM-DD',locale: 'id',Default: true });
                // inisial select input
                $('.select2').select2({ dropdownParent: $('#formCreate') });
            },
            // mounted end
            methods: {
                index() {
                    const _this = this;
                    _this.table = $('#table').DataTable({
                        processing: true,
                        responsive: true,
                        serverSide: true,
                        ajax: this.api + '?type=' + 1,
                        columns: this.columns,
                    })
                    .on("xhr", function () {
                        // isi variabel data dengan data yang ada pada datatable
                        _this.datas = _this.table.ajax.json().data;
                        // console.log(_this.datas)
                    });
                },
                // menampilkan form penjualan baru
                create() {
                    this.data = {};
                    this.method = false;
                    // tampilkan modal box
                    $('#formCreate').modal();
                    // kosongkan semua inputan
                    document.getElementById("resetFormCreate").reset();
                    // kosongkan table order
                    $(".rowOrder").remove();
                },
                // menyimpan penjualan baru
                store(event) {
                    event.preventDefault();
                    axios
                        .post(action, new FormData( $(event.target)[0] ) )
                        .then( (response) => {
                            $("#formCreate").modal("hide");
                            const text = "Transaksi berhasil ditambahkan!";
                            $("#formInvoice").modal("show");
                            app.notifySuccess(text);
                            this.table.ajax.reload();
                            this.order = response.data[1].products;
                            this.totalHarga = response.data[0];
                            this.transaction = response.data[1].id;
                        })
                        .catch( function(fouls) {
                            let messages = fouls.response.data.errors;
                            app.nofifyError(messages);
                        });
                },
                show(id) {
                    const _this = this
                    $("#showBox").modal();
                    const url = '{!! url('get/penjualan') !!}' + '?id=' + id;
                    axios
                        .get( url )
                        .then(function (response) {
                            // handle success
                            _this.data = response.data

                            const arryProduk = _this.data.products;
                            for (let i = 0; i < arryProduk.length; i++) {
                                const element = arryProduk[i];
                                const total = element.price * element.pivot.quantity
                                _this.totalHarga += total;
                            }
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        })
                },
                edit(id) {
                    const _this = this
                    $("#editForm").modal();
                    const url = '{!! url('get/penjualan') !!}' + '?id=' + id;
                    axios
                        .get( url )
                        .then(function (response) {
                            // handle success
                            _this.data = response.data
                            const arryProduk = _this.data.products;
                            for (let i = 0; i < arryProduk.length; i++) {
                                const element = arryProduk[i];
                                const total = element.price * element.pivot.quantity
                                _this.totalHarga += total;
                            }
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        })
                    $(".editedRow").remove();
                },
                update(event, id) {
                    event.preventDefault();
                    const url = action + '/' + id;
                    axios
                        .put(url, new FormData( $(event.target)[0] ) )
                        .then( (response) => {
                            console.log(response)
                            $('#editForm').modal("hide");
                            app.notifySuccess(response);
                            this.table.ajax.reload();
                        })
                        .catch( function (fouls) {
                            // console.log(error)
                            let messages = fouls.response.data.errors;
                            app.nofifyError(messages);
                        });

                },
                destroy(event, id) {

                },
                payment(event) {
                    event.preventDefault();
                    const url = '{{url('payment')}}' + '?id=' + this.transaction;
                    axios
                        .post( url , new FormData($(event.target)[0]))
                        .then((response) => {
                            const text = "Pembayaran Berhasil";
                            $("#formInvoice").modal("hide");
                            app.notifySuccess(text)
                            this.table.ajax.reload();
                        })
                        .catch(function (fouls) {
                            let messages = fouls.response.data.errors;
                            app.nofifyError(messages);
                        });
                },
                notifySuccess(text) {
                    Swal.fire({
                        title: "Mantap",
                        icon: "success",
                        text: text,
                    });
                },
                nofifyError (messages) {
                    let error = "";
                    $.each(messages, function (indexInArray, valueOfElement) {
                        error += `<div class='text-danger'> ${valueOfElement}</div> <br>`;
                    });

                    Swal.fire({
                        title: "Proses Gagal!",
                        icon: "error",
                        html: error,
                        confirmButtonText: "Ulangi",
                    });

                },
                hitungKembalian(event) {
                    this.kembalian = event.target.value - this.totalHarga;
                    $("#kembalian").val(this.kembalian);
                },
                hapusOrder(event){
                    var rmOrder = $(".rmOrder");
                    // ambil index dari elemen yang diklik
                    const id = Array.from(rmOrder).indexOf(event.target)
                    console.log(id)
                    // inisialisasi tabel row sesuai dengan index elemen yang diklik
                    var rowOrder = $(".rowOrder")[id];
                    // hapus tabel row tersebut
                    rowOrder.remove();
                },
                selectProduct() {
                    const _this = this;
                    const id = this.product_id - 1
                    const product = this.products[id];
                    $(".pesanan").append(`
                        <tr class="rowOrder editedRow">
                            <td>${product.code}</td>
                            <td>${product.name}</td>
                            <td>${product.price}</td>
                            <td>
                                <input type="number" name="quantity[${product.id}]" class="form-control form-control-sm qty" value="1">
                                <input type="hidden" name="product_id[]" class="form-control form-control-sm" value="${product.id}">
                            </td>
                            <td>
                                <span class="btn btn-sm btn-danger" onclick="app.hapusOrder(event)">
                                    <i class="fas fa-trash rmOrder"></i>
                                </span>
                            </td>
                        </tr>
                    `);

                },
            },
            // methods end
         })
     </script>
@endpush
