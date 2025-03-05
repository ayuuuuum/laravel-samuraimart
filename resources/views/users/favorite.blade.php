@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>お気に入り</h1>

            <hr class="my-4">

            @if ($favorite_products->isEmpty())
                <div class="row">
                    <p class="mb-0">お気に入りはまだ追加されていません。</p>
                </div>
            @else
                @foreach ($favorite_products as $favorite_product)
                    <div class="row align-items-center mb-2">
                        <div class="col-md-2">
                            <a href="{{ route('products.show', $favorite_product->id) }}">
                                @if ($favorite_product->image !== "")
                                    <img src="{{ asset($favorite_product->image) }}" class="img-thumbnail samuraimart-product-img-cart">
                                @else
                                    <img src="{{ asset('img/dummy.png') }}" class="img-thumbnail samuraimart-product-img-cart">
                                @endif
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h5 class="w-100 samuraimart-favorite-item-text"><a href="{{ route('products.show', $favorite_product->id) }}" class="link-dark">{{ $favorite_product->name }}</a></h5>
                            <h6 class="w-100 samuraimart-favorite-item-text mb-0">￥{{ number_format($favorite_product->price) }}</h6>
                        </div>
                        <div class="col-md-1">
                            <form id="favorites-destroy-form" action="{{ route('favorites.destroy', $favorite_product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link link-dark text-decoration-none">
                                    削除
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form method="POST" action="{{route('carts.store')}}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $favorite_product->id }}">
                                <input type="hidden" name="name" value="{{ $favorite_product->name }}">
                                <input type="hidden" name="price" value="{{ $favorite_product->price }}">
                                <input type="hidden" name="image" value="{{ $favorite_product->image }}">
                                <input type="hidden" name="carriage" value="{{ $favorite_product->carriage_flag }}">
                                <input type="hidden" name="qty" value="1">
                                <input type="hidden" name="weight" value="0">
                                <button type="submit" class="btn samuraimart-submit-button w-100 text-white">
                                    <i class="fas fa-shopping-cart"></i>
                                    カートに追加
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif

            <hr class="my-4">

            <div class="mb-4">
                {{ $favorite_products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection