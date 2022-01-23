{{-- modal box --}}
<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Curriculum Vitae</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header bg-secondary">
                            <h5 class="card-title">Biodata</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td>: @{{data.name}}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: @{{data.email}}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>: @{{data.phone}}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>: @{{data.address}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
    </div>
</div>
{{-- modal box end --}}
