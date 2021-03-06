@extends('layouts.app')
@section('title', 'Penyuplai')
@section('content-title', 'Penyuplai')
@section('card-header', 'Daftar Penyuplai')
@section('breadcrumb', 'Penyuplai')
@section('sub-breadcrumb', 'Daftar Penyuplai')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-2">
            <a href="#" @click.prevent="create()" class="btn btn-primary">Tambah Penyuplai</a>
        </div>
    </div>
    <hr>
    {{-- data content --}}
    <table class="table table-bordered table-sm text-center w-100" id="table">
        <thead>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Opsi</th>
        </thead>
    </table>
    {{-- data content end --}}

    {{-- modal box --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form :action="action" method="POST" v-on:submit="submitForm( $event, data.id )">
                @csrf
                <input type="hidden" name="_method" value="PUT" v-if="method">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nama Penyuplai</label>
                            <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Masukan Kode Penyuplai" :value="data.company_name">
                        </div>
                        <div class="mb-3">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" class="form-control" name="email" id="email"  placeholder="Masukan email" :value="data.email">
                        </div>
                        <div class="mb-3">
                          <label for="phone" class="form-label">Telepon</label>
                          <input type="text" name="phone" id="phone" class="form-control" placeholder="Masukan Nomor Telepon" :value="data.phone">
                        </div>
                        <div class="mb-3">
                          <label for="address" class="form-label">Alamat</label>
                          <textarea class="form-control" name="address" id="address" rows="3">@{{data.address}}</textarea>
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
@endsection

@push('script')
    {{-- datatables script --}}
    <script src="{{asset('adminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

    {{-- sweetalert CDN --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var action = '{{url('suppliers')}}';
        var api = '{{url('datatable/suppliers')}}';
        var columns = [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "company_name", name: "company_name" },
            { data: "email", name: "email" },
            { data: "phone", name: "phone" },
            { data: "address", name: "address" },
            { data: "action", name: "action", orderable:false, searchable: false }
        ];

        var app = new Vue({
            el: '#app',
            data: {
                datas: [],
                data: {},
                action: action,
                api: api,
                method: false,
                message: "",
            },
            mounted: function () {
                this.datatable();
            },
            methods: {
                datatable() {
                    const _this = this;
                    _this.table = $("#table")
                        .DataTable({
                            ajax: {
                                url: _this.api,
                                type: "GET",
                            },
                            columns,
                        })
                        .on("xhr", function () {
                            _this.datas = _this.table.ajax.json().data;
                        });
                },
                create() {
                    this.data = {};
                    this.method = false;
                    $("#exampleModal").modal();
                    $(".modal-title").text("Tambah Penyuplai");
                },
                edit(event, id) {
                    const _this = this
                    var url = action + '/' + id + '/edit';
                    _this.method = true;
                    $(".modal-title").text("Edit Gudang");
                    $("#exampleModal").modal();
                    axios.get(url)
                        .then(function (response) {
                            _this.data = response.data;
                        })
                },
                destroy(event, id) {
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
                            id - 1;
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
                    id - 1;
                    var url = !this.method ? this.action : this.action + "/" + id;
                    this.message = !this.method
                        ? "Penyuplai Berhasil ditambahkan"
                        : "Penyuplai berhasil diubah";
                    axios
                        .post(url, new FormData($(event.target)[0]))
                        .then((response) => {
                            $("#exampleModal").modal("hide");
                            _this.table.ajax.reload();
                            Swal.fire(this.message);
                            this.action = action;
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
            },
        })
    </script>
@endpush
