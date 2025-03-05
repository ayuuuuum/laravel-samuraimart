@extends('layouts.app')

@section('content')
<div class="container pt-2">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row">
                <nav class="mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('top') }}">トップ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    @if ($product->image)
                        <img src="{{ asset($product->image) }}" class="img-thumbnail samuraimart-product-img-detail">
                    @else
                        <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail samuraimart-product-img-detail">
                    @endif
                </div>
                <div class="col">
                    <div>
                        <h1>
                            {{$product->name}}
                        </h1>
                        <p>
                            {{$product->description}}
                        </p>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex align-items-baseline">
                        <span class="fs-4 fw-bold">￥{{ number_format($product->price) }}</span><span class="small">（税込）</span>
                    </div>

                    <hr class="my-4">

                    @auth
                        <form method="POST" action="{{route('carts.store')}}" class="align-items-end">
                            @csrf
                            <input type="hidden" name="id" value="{{$product->id}}">
                            <input type="hidden" name="name" value="{{$product->name}}">
                            <input type="hidden" name="price" value="{{$product->price}}">
                            <input type="hidden" name="image" value="{{$product->image}}">
                            <input type="hidden" name="carriage" value="{{$product->carriage_flag}}">
                            <div class="form-group row mb-3 pt-2">
                                <label for="quantity" class="col-sm-2 col-form-label">数量</label>
                                <div class="col-sm-10">
                                    <input type="number" id="quantity" name="qty" min="1" value="1" class="form-control w-25 samuraimart-form-parts">
                                </div>
                            </div>
                            <input type="hidden" name="weight" value="0">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn samuraimart-submit-button w-100 text-white">
                                        <i class="fas fa-shopping-cart"></i>
                                        カートに追加
                                    </button>
                                </div>
                                <div class="col">
                                    @if(Auth::user()->favorite_products()->where('product_id', $product->id)->exists())
                                        <a href="{{ route('favorites.destroy', $product->id) }}" class="btn samuraimart-favorite-button text-favorite w-100" onclick="event.preventDefault(); document.getElementById('favorites-destroy-form').submit();">
                                            <i class="fa fa-heart"></i>
                                            お気に入り解除
                                        </a>
                                    @else
                                        <a href="{{ route('favorites.store', $product->id) }}" class="btn samuraimart-favorite-button text-favorite w-100" onclick="event.preventDefault(); document.getElementById('favorites-store-form').submit();">
                                            <i class="fa fa-heart"></i>
                                            お気に入り
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <form id="favorites-destroy-form" action="{{ route('favorites.destroy', $product->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                        <form id="favorites-store-form" action="{{ route('favorites.store', $product->id) }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endauth
                </div>
            </div>

            <hr class="mb-4">

            <div class="row">
                <h2 class="float-left">カスタマーレビュー</h2>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 mb-4">
                    <div class="mb-4">
                        <p>{{ number_format($reviews->total()) }}件のレビュー</p>
                    </div>

                    @auth
                        <form method="POST" action="{{ route('reviews.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="fs-5 mb-1">評価</label>
                                <select name="score" class="form-control review-score-color form-select samuraimart-form-parts">
                                    <option value="5" class="review-score-color">★★★★★</option>
                                    <option value="4" class="review-score-color">★★★★</option>
                                    <option value="3" class="review-score-color">★★★</option>
                                    <option value="2" class="review-score-color">★★</option>
                                    <option value="1" class="review-score-color">★</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="fs-5 mb-1">タイトル</label>
                                @error('title')
                                    <p class="text-danger">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </p>
                                @enderror
                                <input type="text" class="form-control samuraimart-form-parts" name="title">
                            </div>
                            <div class="mb-4">
                                <label class="fs-5 mb-1">レビュー内容</label>
                                @error('content')
                                    <p class="text-danger">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </p>
                                @enderror
                                <textarea name="content" class="form-control samuraimart-form-parts" rows="4"></textarea>
                            </div>
                            <input type="hidden" name="product_id" value="{{$product->id}}">
                            <button type="submit" class="btn samuraimart-submit-button text-white w-100">レビューを追加</button>
                        </form>
                    @endauth
                </div>

                <div class="col">
                    @if ($reviews->isEmpty())
                        <p>レビューはまだありません。</p>
                    @else
                        @foreach($reviews as $review)
                            <div class="mb-5">
                                <h3>{{ $review->title }}</h3>
                                <p class="fs-5 mb-2"><span class="review-score-color">{{ str_repeat('★', $review->score) }}</span><span class="review-score-blank-color">{{ str_repeat('★', 5 - $review->score) }}</span></p>
                                <p>{{$review->content}}</p>
                                <p><span class="fw-bold me-2">{{$review->user->name}}</span><span class="text-muted">{{ $review->created_at->format('Y年m月d日') }}</span></p>
                            </div>
                        @endforeach

                        <div class="mb-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection