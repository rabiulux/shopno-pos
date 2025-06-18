@extends('layouts.sidenav-layout')
@section('content')
    @include('components.purchase.purchase-list')
    @include('components.purchase.purchase-delete')
    @include('components.purchase.purchase-details')
@endsection
