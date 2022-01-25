@extends('layouts.app')
@section('title', 'Pembayaran')
@section('content-title', 'Pembayaran')
@section('card-header', 'Daftar Pembayaran')
@section('breadcrumb', 'Pembayaran')
@section('sub-breadcrumb', 'Daftar Pembayaran')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-2">
            <a href="#" v-on:click="create()" class="btn btn-primary">Tambah Pembayaran</a>
        </div>
    </div>
    <hr>
    {{-- data content --}}
    <table class="table table-bordered table-sm text-center" id="table_id">
        <thead>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode Pembayaran</th>
            <th>Kode Pembelian</th>
            <th>Jumlah Bayar</th>
            <th>Aksi</th>
        </thead>
    </table>
    {{-- data content end --}}

    {{-- modal box --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form :action="action" method="POST" @submit="submitForm( $event, data.id )">
                @csrf
                <input type="hidden" name="_method" value="PUT" v-if="method">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-6">
                            <div class="mb-3">
                              <label for="kode_pembelian" class="form-label">Kode Pembelian (PO)</label>
                              <select class="form-control select2" name="kode_pembelian" id="kode_pembelian" onchange="app.addPurchase(event)">
                                <option value="">Pilih Kode Pembelian</option>
                                @foreach ($purchases as $purchase)
                                    <option value="{{$purchase->id}}">{{$purchase->code}}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="deadline" class="form-label">Deadline Pembayaran</label>
                                <input type="date" name="deadline" id="deadline" class="form-control" :value="data.payment_deadline" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="total" class="form-label">Total Tagihan</label>
                                <input type="text" name="total" id="total" class="form-control" :value="data.grand_total" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="paid" class="form-label">Terbayar</label>
                                <input type="text" name="paid" id="paid" class="form-control" :value="data.total_payment" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="sisa_tagihan" class="form-label">Sisa Tagihan</label>
                                <input type="number" name="sisa_tagihan" id="sisa_tagihan" class="form-control" :value="data.grand_total - data.total_payment" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="nominal_pembayaran" class="form-label">Nominal</label>
                                <input type="number" name="nominal_pembayaran" id="nominal_pembayaran" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- modal box end --}}
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
        var action = '{{url('payments')}}';
        var api = '{{url('datatable/payments')}}';
        var columns = [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "created_at", name: "created_at"},
            { data: "code", name: "code"},
            { data: "purchase-code", name: "purchase-code" },
            { data: "amount", name: "amount"},
            { data: "action", name: "action", orderable: false, searchable:false}
        ];
        var app = new Vue({
            el: '#app',
            data: {
                datas: [], // variable untuk menyimpan seluruh data produk
                data: {}, // variable untuk menyimpan satu produk untuk crud
                action,
                api,
                method: false, // method untuk crud data
                message: "", // pesan dalam sweetalert
            },
            mounted: function () {
                this.datatable();
                $('.select2').select2({ theme: 'bootstrap4', dropdownParent: $('#exampleModal') });
            },
            methods: {
                datatable() {
                    const _this = this;
                    _this.table = $("#table_id")
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
                    const _this = this;
                    _this.data = {}; // kosongkan variable data
                    _this.method = false; // hilangkan element input yang namanya _method
                    $("#exampleModal").modal(); // tampilkan modal
                    $(".modal-title").text("Tambah Produk"); // ganti title modal
                    $("#nominal_pembayaran").val(0);
                    $("#sisa_tagihan").val(0);
                },
                edit(event, id) {
                    const productId = id - 1;
                    this.data = this.datas[productId]; // isi variable data berdasarkan id dari parameter
                    // console.log(this.data) testing
                    this.method = true; // tampilkan elemen input yang namanya _method (untuk handle request method PUT)
                    $("#exampleModal").modal(); // tampilkan modal box
                    $(".modal-title").text("Edit Produk"); // ganti title modal
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
                submitForm(event, id) {
                    event.preventDefault();
                    const _this = this;
                    var action = !this.method ? this.action : this.action + "/" + id;
                    this.message = !this.method
                        ? "Produk Berhasil ditambahkan"
                        : "Produk berhasil diubah";
                    axios
                        .post(action, new FormData($(event.target)[0]))
                        .then((response) => {
                            console.log(response)
                            $("#exampleModal").modal("hide");
                            _this.table.ajax.reload();
                            Swal.fire(this.message);
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
                addPurchase(e) {
                    const _this = this;
                    const id = e.target.value;
                    const url = '{!! url('get/purchase') !!}' + '/' + id;
                    axios
                    .get(url)
                        .then((response) => {
                            console.log(response.data)
                            _this.data = response.data;
                        })
                        .catch(function(error) {
                            console.log(error)
                        })
                }
            },
        })
    </script>
@endpush
