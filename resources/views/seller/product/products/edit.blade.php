@extends('seller.layouts.app')

@section('panel_content')

@include('seller.product.products.form', [
    'action' => route('seller.products.update', $product->id),
    'method' => 'PUT',
    'product' => $product
])

@endsection
