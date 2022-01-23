@extends('layouts.app')
@section('title', 'Profil')
@section('content-title', 'Profil')
@section('card-header', 'Profil')
@section('breadcrumb', 'Profil')
@section('sub-breadcrumb', 'Profil')

@section('content')
<div class="row">
    <div class="col-5">
        <form action="{{ route( 'users.update', ['user' => $user->id ] ) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header bg-secondary">
                    <h4 class="card-title">Biodata</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Isi nama lengkap" aria-describedby="user_name" value="{{ old('name', $user->name) }}">
                        @error('name')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelpId" placeholder="Isi email valid" value="{{ old('email', $user->email) }}">
                        @error('email')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Isi nomor telepon" aria-describedby="phonehelpId" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" name="address" id="address" rows="2">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </form>
        <form action="{{ url('password/reset/manual') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header bg-secondary">
                    <h4 class="card-title">Keamanan</h4>
                </div>
                <div class="card-body row">
                    <div class="col-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                        <small id="helpId" class="text-muted">Masukan password minimal 6 digit</small>
                    </div>
                    <div class="col-6">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-danger">Reset</button>
                </div>
            </div>
        </form>
    </div> <!-- col-5 end -->
</div>
@endsection

@push('script')
    {{-- sweetalert CDN --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (Session::get('success'))
        <script>
            Swal.fire({
                title: 'Mantap',
                icon: 'success',
                text: '{!! Session::get('success') !!}',
            });
        </script>
    @endif

    @error('password')
    <script>
        Swal.fire({
            title: 'Ooops',
            icon: 'error',
            text: '{!! $message !!}',
        });
    </script>
    @enderror

@endpush
