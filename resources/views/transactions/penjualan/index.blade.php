@extends('layouts.app')
@section('title', 'Penjualan')
@section('content-title', 'Penjualan')
@section('card-header', 'Riwayat Penjualan')
@section('breadcrumb', 'Penjualan')
@section('sub-breadcrumb', 'Riwayat Penjualan')

@section('content')
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
    @if (Session::get('success'))
        <script>
            Swal.fire({
                title: 'Mantap',
                icon: 'success',
                text: '{!! Session::get('success') !!}',
            });
        </script>
    @endif
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
                datas: [],
                data: {},
                api: api,
                columns: columns,
                products : JSON.parse('{!! $products !!}'),
                product_id:{},
                order: {},
                totalHarga: 0,
                pembayaran: 0,
                kembalian: 0,
                transaction: 0
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
                        // isi variabel datas dengan data yang ada pada datatable
                        _this.datas = _this.table.ajax.json().data;
                        // console.log(_this.datas) testing
                    });
                },
                // menampilkan form penjualan baru
                create() {
                    // kosongkan data
                    this.data = {};
                    // tampilkan modal box
                    $('#formCreate').modal();
                    // kosongkan semua inputan
                    document.getElementById("resetFormCreate").reset();
                    // kosongkan table order
                    $(".rowOrder").remove();
                },
                // simpan transaksi ke database
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
                // menampilkan detil penjualan
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
                // menghapus penjualan
                destroy(id) {
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
                // melakukan pembayaran
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
                // notifikasi proses berhasil
                notifySuccess(text) {
                    Swal.fire({
                        title: "Mantap",
                        icon: "success",
                        text: text,
                    });
                },
                // notifikasi proses gagal
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
                // menghitung kembalian
                hitungKembalian(event) {
                    this.kembalian = event.target.value - this.totalHarga;
                    $("#kembalian").val(this.kembalian);
                },
                // menghapus ordes tertentu dari daftar cart
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
                // menambahkan produk baru
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
