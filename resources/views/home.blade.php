@extends('layouts.app')
@section('title', 'Dashoard')
@section('content-title', 'Dashoard')
@section('card-header', 'Grafik')
@section('breadcrumb', 'Dashoard')
@section('sub-breadcrumb', 'Grafik')

@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    {{ __('You are logged in!') }}

@endsection
