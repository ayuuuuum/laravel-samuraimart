@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-5">ショッピングカート</h1>

            <div class="row">
                <div class="col-md-8">
                    <span class="fs-5 fw-bold">商品</span>
                </div>
                <div class="col-md-2">
                    <span class="fs-5 fw-bold">数量</span>
                </div>
                <div class="col-md-2">
                    <span class="fs-5 fw-bold">合計</span>
                </div>
            </div>

            <hr class="my-4">

            @if ($cart->isEmpty())
                <div class="row">
                    <p class="mb-0">カートの中身は空です。</p>
                </div>
            @else
                @foreach ($cart as $product)
                    <div class="row align-items-center mb-2">
                        <div class="col-md-2">
                            <a href="{{ route('products.show', $product->id) }}">
                                @if ($product->options->image)
                                    <img src="{{ asset($product->options->image) }}" class="img-thumbnail samuraimart-product-img-cart">
                                @else
                                    <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail samuraimart-product-img-cart">
                                @endif
                            </a>
                        </div>
                        <div class="col-md-6">
                            <span class="fs-5">
                                <a href="{{ route('products.show', $product->id) }}" class="link-dark">{{ $product->name }}</a>
                            </span>
                        </div>
                        <div class="col-md-2">
                            <span class="fs-5">{{ number_format($product->qty) }}</span>
                        </div>
                        <div class="col-md-2">
                            <span class="fs-5">￥{{ number_format($product->qty * $product->price) }}</span>
                        </div>
                    </div>
                @endforeach
            @endif

            <hr class="my-4">

            <div class="row justify-content-end">
                <div class="col-md-2">
                    <span class="fs-5 fw-bold">送料</span>
                </div>
                <div class="col-md-2">
                    <span class="fs-5">￥{{ number_format($carriage_cost) }}</span>
                </div>
            </div>

            <hr class="my-4">

            <div class="row justify-content-end mb-4">
                <div class="col-md-2">
                    <span class="fs-5 fw-bold">合計</span>
                </div>
                <div class="col-md-2">
                    <span class="fs-5 fw-bold">￥{{ number_format($total) }}</span>
                </div>
            </div>

            <div class="row mb-4">
                <p class="text-end mb-1">表示価格は税込みです。</p>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-3 mb-4">
                    <a href="{{route('products.index')}}" class="btn samuraimart-favorite-button text-favorite w-100">
                        買い物を続ける
                    </a>
                </div>

                <div class="col-md-3 mb-4">
                    @if ($total > 0)
                        <a href="{{ route('checkout.index') }}" class="btn samuraimart-submit-button text-white w-100">購入に進む</a>
                    @else
                        <button class="btn samuraimart-submit-button-disabled w-100">購入に進む</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection