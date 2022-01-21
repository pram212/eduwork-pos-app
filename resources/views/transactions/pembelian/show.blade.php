<!-- Modal -->
<div class="modal fade" id="showBox" tabindex="-1" aria-labelledby="showBoxLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="showBoxLabel">Detil Penjualan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-sm">
                        <tr class="bg-secondary">
                            <th colspan="2">Detil Transaksi</th>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: @{{data.date}}</td>
                        </tr>
                        <tr>
                            <th>Kode</th>
                            <td>: @{{data.voucher}}</td>
                        </tr>
                        <tr>
                            <th>Penyuplai</th>
                            <td>: @{{data.supplier}}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>: @{{ data.description }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-sm">
                        <thead>
                            <tr class="bg-secondary">
                                <th colspan="4">
                                    Produk
                                </th>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in data.products">
                                <td><i>@{{ product.name }}</i></td>
                                <td><i>Rp @{{ product.price }}</i></td>
                                <td><i>@{{ product.pivot.quantity }} pcs</i></td>
                                <td>: <i>@{{ product.pivot.quantity * product.price }}</i></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end bold"></td>
                                <th>Total</th>
                                <td>: <i>@{{ totalHarga }}</i></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end bold"></td>
                                <th>Tunai</th>
                                <td>: <i>@{{ data.payment }}</i></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end bold"></td>
                                <th>Kembalian</th>
                                <td>: <i>@{{ data.refund }}</i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
