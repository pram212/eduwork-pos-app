@extends('layouts.app')
@section('title', 'Role')
@section('content-title', 'Role')
@section('card-header', 'Daftar Role')
@section('breadcrumb', 'Role')
@section('sub-breadcrumb', 'Daftar Role')
@section('content')
    <div class="row mb-2">
        <div class="col">
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></button>
                <a href="#" @click.prevent="store()" class="btn btn-primary btn-sm">Role Baru</a>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-secondary btn-sm"><i class="fas fa-cog"></i></button>
                <a href="#" @click.prevent="createAsignRole()" class="btn btn-secondary btn-sm">Atur Role User</a>
            </div>
        </div>
    </div>
    <hr>
    {{-- data content --}}
    <table class="table table-bordered table-sm text-center w-100" id="table">
        <thead>
            <th>No</th>
            <th>Nama</th>
            <th>User</th>
            <th>Aksi</th>
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
                            <label for="name" class="form-label">Nama Role</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Masukan Kode Role" :value="data.name">
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

    {{-- form permissions --}}
    @include('roles_permissions.form-permissions')
    {{-- /. form permissions --}}

    {{-- form permissions --}}
    @include('roles_permissions.form_asign_role')
    {{-- /. form permissions --}}

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
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
    <script src="{{asset('adminLTE/plugins/select2/js/select2.full.min.js')}}"></script>

    {{-- sweetalert CDN --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var action = '{{url('roles-permissions')}}';
        var api = '{{url('datatable/roles-permissions')}}';
        var columns = [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "name", name: "name" },
            { data: "users", name: "users" },
            { data: "action", name: "action", orderable:false, searchable: false }
        ];

        var app = new Vue({
            el: '#app',
            data: {
                datas: [], // objek-objek untuk datatable
                data: {}, // sebuah objek untuk crud data
                action: action,
                api: api,
                method: false, // method untuk crud data
                message: "", // pesan dalam sweetalert
            },
            mounted: function () {
                this.datatable();
                //Initialize Select2 Elements
                $('.selectUser').select2({ theme: 'bootstrap4', dropdownParent: $('#formAsignRole') })
                $('.selectRole').select2({ theme: 'bootstrap4', dropdownParent: $('#formAsignRole') })
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
                    $("#exampleModal").modal();
                    $(".modal-title").text("Tambah Role");
                },
                update(event, id) {
                    this.data = this.datas[id - 1];
                    console.log(this.data)
                    this.method = true;
                    $(".modal-title").text("Edit Role");
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
                    id -1;
                    var url = !_this.method ? _this.action : _this.action + "/" + id
                    this.message = !this.method
                        ? "Role Berhasil ditambahkan"
                        : "Role berhasil diubah";
                    axios
                        .post(url, new FormData($(event.target)[0]))
                        .then((response) => {
                            $("#exampleModal").modal("hide");
                            _this.table.ajax.reload();
                            Swal.fire(this.message);
                            _this.action = action;
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
                editPermission(e, id) {
                    const _this = this;
                    _this.data = {};
                    $("#formPermission").modal();
                    url = '{!! url('get/permission') !!}' + '/' + id;
                    axios
                        .get(url)
                        .then(function(response){
                            id -= 1;
                            _this.data = _this.datas[id]
                            _this.checkPermission(response.data);
                        })
                        .catch(function(error) {
                            console.log(error);
                        })

                },
                checkPermission(data) {
                    const _this = this
                    var inputElement = $(".input-permission");
                    for (let i = 0; i < inputElement.length; i++) {
                        const element = inputElement[i];
                        element.checked = false;
                        const id = element.id;
                        if(data.includes(id)) {
                            element.checked = true;
                        }
                    }
                },
                storePermission(e, id) {
                    const _this = this
                    const url = '{!! url('roles-permissions/setup-permissions') !!}' + '/' + id;
                    axios
                        .post(url, new FormData($(e.target)[0]))
                        .then((response) => {
                            $("#formPermission").modal("hide");
                            _this.table.ajax.reload();

                            Swal.fire({
                                title : 'Mantap',
                                icon : 'success',
                                text : response.data
                            });

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
                createAsignRole() {
                    $("#formAsignRole").modal();
                },
                storeAsignRole(e) {
                    const _this = this
                    const url = '{!! url('roles-permissions/assign-role') !!}';
                    axios
                        .post(url, new FormData($(e.target)[0]))
                        .then((response) => {
                            $("#formAsignRole").modal("hide");
                            _this.table.ajax.reload();
                            Swal.fire({
                                title : 'Mantap',
                                icon : 'success',
                                text : response.data
                            });

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
                }
            },
        })
    </script>
@endpush
