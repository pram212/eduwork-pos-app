<!-- modal invoice -->
<div class="modal fade" id="formInvoice" tabindex="-1" aria-labelledby="formInvoiceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" v-on:submit="payment($event)">
            @csrf
            <input type="hidden" name="id" :value="transaction">
            <div class="modal-body">
                <div class="mb-3">
                  <label for="totalharga" class="form-label">Total Tagihan</label>
                  <input type="number" name="totalharga" id="totalharga" class="form-control" :value="totalHarga">
                </div>
                <div class="mb-3">
                    <label for="pembayaran" class="form-label">Pembayaran</label>
                    <input type="number" name="pembayaran" id="pembayaran" class="form-control" v-on:keyup="hitungKembalian($event)">
                </div>
                <div class="mb-3">
                    <label for="kembalian" class="form-label">Kembalian</label>
                    <input type="number" name="kembalian" id="kembalian" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Bayar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal invoice -->
