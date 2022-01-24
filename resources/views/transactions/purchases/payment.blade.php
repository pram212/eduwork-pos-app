<div class="modal fade" id="formBayar" tabindex="-1" role="dialog" aria-labelledby="formBayarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formBayarLabel">Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form :action="action" method="POST" @submit.prevent="simpanPembayaran($event, data.id)">
                @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="code" class="form-label">Kode Pembelian</label>
                    <input type="text" name="code" id="code" class="form-control" :value="data.code" readonly>
                </div>
                <div class="mb-3">
                    <label for="tagihan" class="form-label">Total Tagihan</label>
                    <input type="text" name="tagihan" id="tagihan" class="form-control" :value="data.grand_total" readonly>
                </div>
                <div class="mb-3">
                    <label for="sisa" class="form-label">Sisa Tagihan</label>
                    <input type="text" name="sisa" id="sisa" class="form-control" :value="data.grand_total - data.payment" readonly>
                </div>
                <div class="mb-3">
                    <label for="pembayaran" class="form-label">Nominal Pembayaran</label>
                    <input type="text" name="pembayaran" id="pembayaran" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Bayar</button>
            </div>
            </form>
        </div>
    </div>
</div>
