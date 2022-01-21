@extends('layouts.app')
@section('title', 'Karyawan')
@section('content-title', 'Karyawan')
@section('card-header', 'Daftar Karyawan')
@section('breadcrumb', 'Karyawan')
@section('sub-breadcrumb', 'Daftar Karyawan')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-2">
            <a href="#" v-on:click="store()" class="btn btn-primary">Tambah Karyawan</a>
        </div>
    </div>
    <hr>
    {{-- data content --}}
    <table class="table table-bordered table-sm text-center" id="table">
        <thead class="bg-dark">
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
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-center" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header bg-secondary">
                                <h5 class="card-title">Biodata</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-4">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" name="name" id="name" class="form-control" :value="data.name">
                                </div>
                                <div class="col-4">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" :value="data.email">
                                </div>
                                <div class="col-4">
                                    <label for="phone" class="form-label">Telepon</label>
                                    <input type="text" name="phone" id="phone" class="form-control" :value="data.phone">
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control" name="address" id="address" rows="3">@{{data.address}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card" v-if="formPassword">
                            <div class="card-header bg-secondary">
                                <h5 class="card-title">Keamanan</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                                    <small id="helpId" class="text-muted">Masukan password minimal 6 digit</small>
                                </div>
                                <div class="col-6">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                                </div>
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
        var action = '{{url('users')}}';
        var api = '{{url('api/users')}}';
        var columns = [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "name", name: "name" },
            { data: "email", name: "email" },
            { data: "phone", name: "phone" },
            { data: "address", name: "address" },
            {
                render: function (i, row, data, meta) {
                    return `
                    <a href="#" onclick="app.update(event, ${meta.row})" class="btn btn-info btn-sm">Edit</a>
                    <a href="#" onclick="app.destroy(event, ${data.id})" class="btn btn-danger btn-sm">Hapus</a>
                `;
                }, orderable: false, searchable:false
            }
        ];

        var app = new Vue({
            el: '#app',
            data: {
                datas: [],
                data: {},
                action: action,
                api: api,
                method: false,
                formPassword: false,
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
                store() {
                    this.data = {};
                    this.method = false;
                    this.formPassword = true;
                    $("#exampleModal").modal();
                    $(".modal-title").text("Tambah Karyawan");
                },
                update(event, id) {
                    this.formPassword = false;
                    this.data = this.datas[id];
                    console.log(this.data)
                    this.method = true;
                    $(".modal-title").text("Edit Karyawan");
                    $("#exampleModal").modal();
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
                        ? "Karyawan Berhasil ditambahkan"
                        : "Karyawan berhasil diubah";
                    axios
                        .post(action, new FormData($(event.target)[0]))
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
