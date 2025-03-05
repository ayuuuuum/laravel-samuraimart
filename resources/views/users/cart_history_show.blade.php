@extends('layouts.app')

@section('content')
<div class="container pt-2">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('mypage') }}">マイページ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mypage.cart_history') }}">注文履歴</a></li>
                    <li class="breadcrumb-item active" aria-current="page">注文履歴詳細</li>
                </ol>
            </nav>

            <h1 class="mb-5">注文履歴詳細</h1>

            <h5 class="fw-bold">ご注文情報</h5>

            <hr class="mb-3">

            <div class="row mb-2">
                <div class="col-5">
                    注文番号
                </div>
                <div class="col-7">
                    {{ $cart_info->code }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-5">
                    注文日時
                </div>
                <div class="col-7">
                    {{ $cart_info->updated_at }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-5">
                    合計金額
                </div>
                <div class="col-7">
                    {{ number_format($cart_info->price_total) }}円
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-5">
                    合計数量
                </div>
                <div class="col-7">
                    {{ number_format($cart_info->qty) }}
                </div>
            </div>

            <h5 class="fw-bold">商品詳細</h5>

            <hr class="mb-3">

            @foreach ($cart_contents as $product)
                <div class="row align-items-center mb-3">
                    <div class="col-md-5">
                        <a href="{{route('products.show', $product->id)}}" class="ml-4">
                            @if ($product->options->image)
                                <img src="{{ asset($product->options->image) }}" class="img-thumbnail samuraimart-product-img-cart-history">
                            @else
                                <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail samuraimart-product-img-cart-history">
                            @endif
                        </a>
                    </div>
                    <div class="col-md-7">
                        <div class="flex-column">
                            <p>{{$product->name}}</p>
                            <div class="row mb-2">
                                <div class="col-2">
                                    数量
                                </div>
                                <div class="col-10">
                                    {{ number_format($product->qty) }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-2">
                                    小計
                                </div>
                                <div class="col-10">
                                    ￥{{ number_format($product->qty * $product->price) }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-2">
                                    送料
                                </div>
                                <div class="col-10">
                                    @if ($product->options->carriage)
                                        ￥800
                                    @else
                                        ￥0
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-2">
                                    合計
                                </div>
                                <div class="col-10">
                                    @if ($product->options->carriage)
                                        ￥{{ number_format(($product->qty * $product->price) + 800) }}
                                    @else
                                        ￥{{ number_format($product->qty * $product->price) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection