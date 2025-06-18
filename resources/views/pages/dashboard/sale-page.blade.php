@extends('layouts.sidenav-layout')
@section('content')
    <div class="container-fluid">
        <div class="row">

            @include('components.sales.bill')

            @include('components.sales.product-list-for-sale')

            @include('components.sales.customer-list-for-sale')

        </div>
    </div>

    @include('components.sales.add-product-to-bill')
    
@endsection
