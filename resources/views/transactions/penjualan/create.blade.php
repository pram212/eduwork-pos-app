<!-- Modal CRUD Box -->
<div class="modal fade" id="formCreate" tabindex="-1" aria-labelledby="formCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Penjualan Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" @submit="store( $event )" id="resetFormCreate">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" name="date" id="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan</label>
                            <input type="text" name="description" id="description" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="product_id[]" class="form-label" >Tambah Produk</label>
                            <select class="form-control" v-model="product_id" @change="selectProduct()">
                            @foreach ($products as $product)
                                <option value="{{$product->id}}">{{$product->code}} | {{$product->name}} ( Rp {{$product->price}} )</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm text-center">
                            <thead>
                                <tr class="bg-dark">
                                    <th colspan="5">Order</th>
                                </tr>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga Jual</th>
                                    <th width="15%">Jumlah</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="pesanan">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
    </div>
</div>
<!-- /. Modal CRUD Box -->
