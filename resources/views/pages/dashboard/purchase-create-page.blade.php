@extends('layouts.sidenav-layout')
@section('content')
    <div class="container-fluid">
        <div class="row">

            @include('components.purchase-create.bill')

            @include('components.purchase-create.product-list-for-sale')

            @include('components.purchase-create.supplier-list-for-sale')

        </div>
    </div>

    @include('components.purchase-create.add-product-to-bill')
    {{-- //modal for adding product to bill --}}

@endsection
