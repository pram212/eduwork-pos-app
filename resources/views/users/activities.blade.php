@extends('layouts.app')
@section('title', 'Aktifitas')
@section('content-title', 'Aktifitas')
@section('card-header', 'Log Aktifitas User')
@section('breadcrumb', 'Aktifitas')
@section('sub-breadcrumb', 'Log Aktifitas User')

@section('content')
    <div class="row">
        <div class="col">
            <table class="table table-bordered text-dark text-center table-sm table-striped" id="table">
                <thead class="bg-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Aktifitas</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@push('script')
    {{-- datatables script --}}
    <script src="{{asset('adminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('adminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

    <script>
        var api = '{{ url('datatable/activities') }}';
        $(function () {
            $('#table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: api,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'user', name: 'user' },
                    { data: 'activity', name: 'activity' },
                ]
            })
        });
     </script>
@endpush
