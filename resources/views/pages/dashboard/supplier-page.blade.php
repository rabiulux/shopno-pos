@extends('layouts.sidenav-layout')
@section('content')
    @include('components.supplier.supplier-list')
    @include('components.supplier.supplier-create')
    @include('components.supplier.supplier-update')
    @include('components.supplier.supplier-delete')
@endsection
