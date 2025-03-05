@extends('layouts.app')

@section('content')
<div class="container pt-2">
    @if (session('flash_message'))
        <div class="row mb-2">
            <div class="col-12">
                <div class="alert alert-light">
                    {{ session('flash_message') }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-2">
            @component('components.sidebar', ['categories' => $categories, 'major_categories' => $major_categories])
            @endcomponent
        </div>
        <div class="col">
            <div class="mb-4">
                <h2>おすすめ商品</h2>
                <div class="row">
                    @foreach ($recommend_products as $recommend_product)
                        <div class="col-md-4">
                            <a href="{{ route('products.show', $recommend_product) }}">
                                @if ($recommend_product->image !== "")
                                    <img src="{{ asset($recommend_product->image) }}" class="img-thumbnail samuraimart-product-img-recommend">
                                @else
                                    <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail samuraimart-product-img-recommend">
                                @endif
                            </a>
                            <div class="row">
                                <div class="col-12">
                                    <p class="samuraimart-product-label mt-2">
                                        <a href="{{ route('products.show', $recommend_product) }}" class="link-dark">{{ $recommend_product->name }}</a>
                                        <br>
                                        <label>￥{{ number_format($recommend_product->price) }}</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <h2>新着商品</h2>
                <div class="row">
                    @foreach ($recently_products as $recently_product)
                        <div class="col-md-3">
                            <a href="{{ route('products.show', $recently_product) }}">
                                @if ($recently_product->image !== "")
                                    <img src="{{ asset($recently_product->image) }}" class="img-thumbnail samuraimart-product-img-products">
                                @else
                                    <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail samuraimart-product-img-products">
                                @endif
                            </a>
                            <div class="row">
                                <div class="col-12">
                                    <p class="samuraimart-product-label mt-2">
                                        <a href="{{ route('products.show', $recently_product) }}" class="link-dark">{{ $recently_product->name }}</a>
                                        <br>
                                        <label>￥{{ number_format($recently_product->price) }}</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <h2>注目商品</h2>
                <div class="row">
                    @foreach ($featured_products as $featured_product)
                        <div class="col-md-3">
                            <a href="{{ route('products.show', $featured_product) }}">
                                @if ($featured_product->image !== "")
                                    <img src="{{ asset($featured_product->image) }}" class="img-thumbnail samuraimart-product-img-products">
                                @else
                                    <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail samuraimart-product-img-products">
                                @endif
                            </a>
                            <div class="row">
                                <div class="col-12">
                                    <p class="samuraimart-product-label mt-2">
                                        <a href="{{ route('products.show', $featured_product) }}" class="link-dark">{{ $featured_product->name }}</a>
                                        <br>
                                        <label>￥{{ number_format($featured_product->price) }}</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection