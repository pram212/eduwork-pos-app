<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Transaksi Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form :action="action" method="POST" @submit.prevent="store()">
                @csrf
                <div class="row">
                    <div class="col-6 card">
                        <div class="card-header">
                              <label for="" class="form-label">Pilih Produk</label>
                              <select class="form-control select2" id="" onchange="app.tambahOrder(event)">
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $p)
                                <option value="{{$p->id}}">{{ $p->code }} - {{ $p->name }}</option>
                                @endforeach
                              </select>
                        </div>
                        <div class="card-body">
                           <table class="table table-sm">
                               <thead class="text-center">
                                   <th>Kode</th>
                                   <th>Nama</th>
                                   <th>Harga</th>
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
                    <div class="col-6 card">
                        <div class="card-header">
                            <p class="card-title">Detil Transaksi</p>
                        </div>
                        <div class="card-body">
                           <div class="row">
                               <div class="col-12">
                                   <div class="mb-3">
                                       <label for="supplier">Supplier</label>
                                       <select class="form-control select2" id="supplier">
                                         <option value="">Pilih Supplier</option>
                                         @foreach ($suppliers as $supplier)
                                         <option value="{{$supplier->id}}">{{ $supplier->company_name }}</option>
                                         @endforeach
                                       </select>
                                   </div>
                               </div>
                               <div class="col-6">
                                   <div class="mb-3">
                                       <label for="deadline">Tanggal Pelunasan</label>
                                       <input type="date" name="deadline" id="deadline" class="form-control" v-model="deadline">
                                   </div>
                               </div>
                               <div class="col-6">
                                   <div class="mb-3">
                                       <label for="grandtotal">Harga Barang</label>
                                       <input type="number" class="form-control form-control-sm" name="grandtotal" :value="grandTotal" readonly>
                                   </div>
                               </div>
                               <div class="col-6">
                                   <div class="mb-3">
                                       <label for="ongkir">Ongkir</label>
                                       <input type="number" name="ongkir" id="ongkir" class="form-control form-control-sm" v-model="ongkir">
                                   </div>
                               </div>
                               <div class="col-6">
                                   <div class="mb-3">
                                       <label for="invoice">Total Tagihan</label>
                                       <input type="number" name="tagihan" id="tagihan" class="form-control form-control-sm" :value="parseInt(ongkir)+parseInt(grandTotal)" readonly>
                                   </div>
                               </div>
                           </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
