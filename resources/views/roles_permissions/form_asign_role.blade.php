{{-- modal box --}}
<div class="modal fade" id="formAsignRole" tabindex="-1" aria-labelledby="formAsignRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form :action="action" method="POST" @submit.prevent="storeAsignRole( $event )">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="formAsignRoleLabel">Registrasi Role User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Pilih Role</label>
                                <select class="selectRole" name="role" data-placeholder="Pilih salah satu role" style="width: 100%;">
                                    <option value="">Pilih</option>
                                    @foreach ($roles as $role)
                                    <option value="{{$role}}">{{$role}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Pilih User</label>
                                <select class="selectUser" multiple="multiple" name="users[]" data-placeholder="Select a State" style="width: 100%;">
                                    <option value="">Pilih</option>
                                    @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
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
