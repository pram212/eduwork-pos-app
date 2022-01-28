{{-- modal box --}}
<div class="modal fade" id="formPermission" tabindex="-1" aria-labelledby="formPermissionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form :action="action" method="POST" @submit.prevent="storePermission( $event, data.id )">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="formPermissionLabel">Permissions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach ($permissions as $permission)
                        <div class="col-4">
                            <div class="from-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" class="input-permission" id="{{$permission->name}}" name="permissions[]" value="{{$permission->name}}">
                                    <label for="{{$permission->name}}">
                                        {{$permission->name}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
