<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="formModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form :action="action" method="POST" @submit.prevent="save($event, data.id)">
                @csrf
                <input type="hidden" name="_method" value="PUT" v-if="method">
                <div class="row">
                    <div class="col-7">
                        <div class="card">
                            <div class="card-header">
                                <div class="mb-3">
                                  <label for="" class="form-label text-info">Pilih Produk</label>
                                  <select class="form-control select2" onchange="app.tambahOrder(event)">
                                    <option value=""></option>
                                    @foreach ($products as $p)
                                    <option value="{{$p->id}}">{{ $p->code }} - {{ $p->name }}</option>
                                    @endforeach
                                  </select>
                                </div>
                            </div>
                            <div class="card-body">
                               <table class="table table-sm">
                                   <thead>
                                       <th colspan="7" class="text-center text-info">Keranjang Belanja</th>
                                   </thead>
                                   <thead class="text-center">
                                       <th width="15%">Kode</th>
                                       <th width="30%">Nama</th>
                                       <th>Harga</th>
                                       <th>Stok</th>
                                       <th width="10%">Jumlah</th>
                                       <th>Total</th>
                                       <th><i class="fas fa-cog"></i></th>
                                   </thead>
                                   <tbody class="text-center">
                                       <tr v-for="(order, index) in orders">
                                           <input type="hidden" name="produk[]" :value="order.product_id">
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
                                                <input type="number" :max="order.stock" v-model="order.quantity" name="quantity[]" class="form-control form-control-sm" id="quantity" @keyup="hitungTotal($event, index)">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" id="subtotal" :value="order.total" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" @click="hapusOrder(index, order)"><i class="fas fa-trash"></i></button>
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
                    </div>
                    <div class="col-5">
                        <div class="card">
                            <div class="card-header">
                                <p class="card-title text-info"><b>Tagihan</b></p>
                            </div>
                            <div class="card-body">
                               <div class="row">
                                   <div class="col-6 mb-2" v-if="method">
                                       <label for="code">Kode Transaksi</label>
                                   </div>
                                   <div class="col-6 mb-2" v-if="method">
                                        <input type="text" class="form-control form-control-sm" :value="data.code" readonly>
                                   </div>
                                   <div class="col-6 mb-2" v-if="method">
                                       <label for="created_at">Tanggal</label>
                                   </div>
                                   <div class="col-6 mb-2" v-if="method">
                                        <input type="text" class="form-control form-control-sm" :value="data.created_at" readonly>
                                   </div>
                                   <div class="col-6 mb-2">
                                        <label for="grandtotal">Grand Total</label>
                                   </div>
                                   <div class="col-6 mb-2">
                                        <input type="number" class="form-control form-control-sm" name="total" :value="grandTotal" readonly>
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
                                <div class="row d-flex justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                    <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-print"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            <div class="modal-footer">
                Copyright &copy; 2021 <a href="#" target="_blank">Pramono</a>. All rights reserved.
            </div>
        </div>
    </div>
</div>
