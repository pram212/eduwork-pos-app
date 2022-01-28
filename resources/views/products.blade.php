@extends('layouts.app')
@section('title', 'Produk')
@section('content-title', 'Produk')
@section('card-header', 'Daftar Produk')
@section('breadcrumb', 'Produk')
@section('sub-breadcrumb', 'Daftar Produk')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-12">
            @can('tambah produk')
            <div class="btn-group">
                <button class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></button>
                <a href="#" @click.prevent="store()" class="btn btn-sm btn-primary">Produk Baru</a>
            </div>
            @endcan
        </div>
    </div>
    <hr>
    {{-- data content --}}
    <table class="table table-bordered table-sm text-center w-100" id="table">
        <thead>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Stok</th>
            <th>Kategori</th>
            <th>Lokasi</th>
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
                    <div class="modal-body row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="number" name="code" id="code" class="form-control" placeholder="Masukan Kode Produk" :value="data.code">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" name="name" id="name"  placeholder="Masukan Nama Produk" :value="data.name">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="cost" class="form-label">Harga Beli</label>
                                <input type="number" name="cost" id="cost" class="form-control" placeholder="Masukan Harga Beli" :value="data.cost">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga Jual</label>
                                <input type="number" name="price" id="price" class="form-control" placeholder="Masukan Harga Jual" :value="data.price">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stok</label>
                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Masukan Jumlah Stok" :value="data.stock">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                              <label for="category_id" class="form-label">Kategori</label>
                              <select class="form-control" name="category_id" id="category_id">
                                  @foreach ($categories as $category)
                                  <option value="{{$category->id}}" :selected="data.category_id == {{$category->id}}">{{$category->name}}</option>
                                  @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                              <label for="warehouse_id" class="form-label">Lokasi Penyimpanan</label>
                              <select class="form-control" name="warehouse_id" id="warehouse_id">
                                  @foreach ($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" :selected="data.warehouse_id == {{$warehouse->id}}">{{$warehouse->name}}</option>
                                  @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <input type="text" name="description" id="description" class="form-control" placeholder="Masukan Deskripsi Produk" :value="data.description">
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
        var action = '{{url('products')}}';
        var api = '{{url('datatable/products')}}';
        var columns = [
            { data: "id", name: "id" },
            { data: "code", name: "code"},
            { data: "name", name: "name"},
            { data: "cost", name: "cost"},
            { data: "price", name: "price"},
            { data: "stock", name: "stock"},
            { data: "category", name: "category"},
            { data: "warehouse", name: "warehouse"},
            { data: "action", name: "action", orderable: false, searchable:false}
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
                    $(".modal-title").text("Tambah Produk");
                },
                update(event, id) {
                    this.data = this.datas[id - 1];
                    console.log(this.data)
                    this.method = true;
                    $(".modal-title").text("Edit Produk");
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
                        ? "Produk Berhasil ditambahkan"
                        : "Produk berhasil diubah";
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
            },
        })
    </script>
@endpush
