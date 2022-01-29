<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form :action="action" method="POST" @submit.prevent="update(data.id)">
                @csrf
                <div class="row">
                    <div class="col-7 card">
                        <div class="card-header">
                            <div class="mb-3">
                              <label for="" class="form-label">Pilih Produk</label>
                              <select class="form-control select3" onchange="app.tambahOrder(event)" disabled>
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $p)
                                <option value="{{$p->id}}">{{ $p->code }} - {{ $p->name }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="card-body">
                           <table class="table table-sm">
                               <thead class="text-center">
                                   <th>Kode</th>
                                   <th>Nama</th>
                                   <th>Harga</th>
                                   <th>Stok</th>
                                   <th>Jumlah</th>
                                   <th>Total</th>
                                   <th>Aksi</th>
                               </thead>
                               <tbody class="text-center">
                                   <tr v-for="(order, index) in orders">
                                       <input type="hidden" name="produk[]" value="1">
                                        <td>
                                            <input type="text" class="form-control form-control-sm" id="kode" :value="order.code" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" id="name" :value="order.name" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" id="harga" :value="order.price" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" id="stock" :value="order.stock" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="quantity[]" v-model="order.quantity" class="form-control form-control-sm" id="quantity" @keyup="hitungTotal($event, index)">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" :value="order.total" id="subtotal" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" @click="hapusOrder(index, order)"><i class="fas fa-trash"></i></button>
                                        </td>
                                   </tr>
                               </tbody>
                               <tfoot>
                                   <tr>
                                       <th colspan="4" class="text-right">Subtotal</th>
                                       <td colspan="2">
                                           <input type="number" class="form-control form-control-sm" :value="grandTotal" readonly>
                                       </td>
                                   </tr>
                               </tfoot>
                           </table>
                        </div>
                    </div>
                    <div class="col-5 card">
                        <div class="card-header">
                            <p class="card-title">Detil Transaksi</p>
                        </div>
                        <div class="card-body">
                           <div class="row">
                               <div class="col-6 mb-2">
                                    <label for="grandtotal">Grand Total</label>
                               </div>
                               <div class="col-6 mb-2">
                                    <input type="number" class="form-control form-control-sm" name="grandtotal" :value="grandTotal" readonly>
                               </div>
                               <div class="col-6 mb-2">
                                    <label for="pembayaran">Pembayaran</label>
                               </div>
                               <div class="col-6 mb-2">
                                    <input type="number" class="form-control form-control-sm" name="pembayaran" v-model="pembayaran" id="pembayaran">
                               </div>
                               <div class="col-6 mb-2">
                                    <label for="kembalian">Kembalian</label>
                               </div>
                               <div class="col-6 mb-2">
                                    <input type="number" class="form-control form-control-sm" name="kembalian" :value="(pembayaran-grandTotal)" readonly>
                               </div>
                           </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Ubah</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
