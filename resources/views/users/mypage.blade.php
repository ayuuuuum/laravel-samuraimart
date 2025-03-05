@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <div class="row justify-content-center mb-4">
            <div class="col-lg-5">
            <h1 class="mb-4">マイページ</h1>

            @if (session('flash_message'))
                <div class="alert alert-light">
                    {{ session('flash_message') }}
                </div>
            @endif

            <hr class="my-0">

            <div class="container">
                <a href="{{route('mypage.edit')}}" class="link-dark">
                    <div class="row justify-content-between align-items-center py-4 samuraimart-mypage-link">
                        <div class="col-1 ps-0 me-3">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                        <div class="col-9 d-flex flex-column">
                            <h3 class="mb-0">会員情報の編集</h3>
                            <p class="mb-0 text-secondary">メールアドレスや住所などを変更できます</p>
                        </div>
                        <div class="col text-end">
                            <i class="fas fa-chevron-right fa-2x text-secondary"></i>
                        </div>
                    </div>
                </a>
            </div>

            <hr class="my-0">

            <div class="container">
                <a href="{{route('mypage.cart_history')}}" class="link-dark">
                    <div class="row justify-content-between align-items-center py-4 samuraimart-mypage-link">
                        <div class="col-1 ps-0 me-3">
                            <i class="fas fa-archive fa-3x"></i>
                        </div>
                        <div class="col-9 d-flex flex-column">
                            <h3 class="mb-0">注文履歴</h3>
                            <p class="mb-0 text-secondary">過去に購入した商品を確認できます</p>
                        </div>
                        <div class="col text-end">
                            <i class="fas fa-chevron-right fa-2x text-secondary"></i>
                        </div>
                    </div>
                </a>
            </div>

            <hr class="my-0">

            <div class="container">
                <a href="{{ route('mypage.edit_password') }}" class="link-dark">
                    <div class="row justify-content-between align-items-center py-4 samuraimart-mypage-link">
                        <div class="col-1 ps-0 me-3">
                            <i class="fas fa-lock fa-3x"></i>
                        </div>
                        <div class="col-9 d-flex flex-column">
                            <h3 class="mb-0">パスワード変更</h3>
                            <p class="mb-0 text-secondary">ログイン時のパスワードを変更します</p>
                        </div>
                        <div class="col text-end">
                            <i class="fas fa-chevron-right fa-2x text-secondary"></i>
                        </div>
                    </div>
                </a>
            </div>

            <hr class="my-0">

            <div class="container">
                <a href="{{ route('logout') }}" class="link-dark" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="row justify-content-between align-items-center py-4 samuraimart-mypage-link">
                        <div class="col-1 ps-0 me-3">
                            <i class="fas fa-sign-out-alt fa-3x"></i>
                        </div>
                        <div class="col-9 d-flex flex-column">
                            <h3 class="mb-0">ログアウト</h3>
                            <p class="mb-0 text-secondary">SAMURAI Martからログアウトします</p>
                        </div>
                        <div class="col text-end">
                            <i class="fas fa-chevron-right fa-2x text-secondary"></i>
                        </div>
                    </div>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

            <hr class="my-0">
        </div>
    </div>
</div>
@endsection