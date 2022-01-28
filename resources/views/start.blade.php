@extends('layouts.app')
@section('title', 'Beranda')
@section('content-title', 'Beranda')
@section('card-header', 'Beranda')
@section('breadcrumb', 'Beranda')
@section('sub-breadcrumb', 'Beranda')

@section('content')
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Selamat Datang, {{ ucwords(Auth::user()->name) }}</h4>
        <p>Sekarang Anda dapat menggunakan fitur-fitur dalam sistem ini sesuai dengan hak akses yag diberikan.</p>
        <hr>
        <p class="mb-0"></p>
    </div>
@endsection
