@extends('layouts.app')

@section('content')
<div class="container pt-2">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('mypage') }}">マイページ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">会員情報の編集</li>
                </ol>
            </nav>

            <h1 class="mb-3">会員情報の編集</h1>

            <hr class="mb-4">

            <form method="POST" action="{{ route('mypage') }}">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="form-group row mb-3">
                    <label for="name" class="col-md-5 col-form-label text-md-left fw-medium">氏名<span class="ms-1 samuraimart-require-input-label"><span class="samuraimart-require-input-label-text">必須</span></span></label>

                    <div class="col-md-7">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror samuraimart-login-input" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus placeholder="侍 太郎">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>氏名を入力してください</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="email" class="col-md-5 col-form-label text-md-left fw-medium">メールアドレス<span class="ms-1 samuraimart-require-input-label"><span class="samuraimart-require-input-label-text">必須</span></span></label>

                    <div class="col-md-7">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror samuraimart-login-input" name="email" value="{{ $user->email }}" required autocomplete="email" placeholder="samurai@samurai.com">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>メールアドレスを入力してください</strong>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="postal_code" class="col-md-5 col-form-label text-md-left fw-medium">郵便番号<span class="ms-1 samuraimart-require-input-label"><span class="samuraimart-require-input-label-text">必須</span></span></label>

                    <div class="col-md-7">
                        <input id="postal_code" type="text" class="form-control @error('postal_code') is-invalid @enderror samuraimart-login-input" name="postal_code" value="{{ $user->postal_code }}" required autocomplete="postal_code" placeholder="150-0043">

                        @error('postal_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>郵便番号を入力してください</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="address" class="col-md-5 col-form-label text-md-left fw-medium">住所<span class="ms-1 samuraimart-require-input-label"><span class="samuraimart-require-input-label-text">必須</span></span></label>

                    <div class="col-md-7">
                        <input id="address" type="text" class="form-control @error('address') is-invalid @enderror samuraimart-login-input" name="address" value="{{ $user->address }}" required autocomplete="address" placeholder="東京都渋谷区道玄坂２丁目１１−１">

                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>住所を入力してください</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label for="phone" class="col-md-5 col-form-label text-md-left fw-medium">電話番号<span class="ms-1 samuraimart-require-input-label"><span class="samuraimart-require-input-label-text">必須</span></span></label>

                    <div class="col-md-7">
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror samuraimart-login-input" name="phone" value="{{ $user->phone }}" required autocomplete="phone" placeholder="03-5790-9039">

                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>電話番号を入力してください</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <hr class="mb-4">

                <button type="submit" class="btn samuraimart-submit-button w-100 text-white">
                    保存
                </button>
            </form>

            <hr class="my-4">

            <div class="text-center">
                <a href="#" class="link-dark" data-bs-toggle="modal" data-bs-target="#deleteUserConfirmModal">
                    退会する
                </a>
            </div>

            <div class="modal fade" id="deleteUserConfirmModal" tabindex="-1" aria-labelledby="deleteUserConfirmModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="deleteUserConfirmModalLabel">本当に退会しますか？</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-center mb-0">一度退会するとデータはすべて削除され、<br>復旧はできません。</p>
                        </div>
                        <form action="{{ route('mypage.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link link-dark text-decoration-none" data-bs-dismiss="modal">キャンセル</button>
                                <button type="submit" class="btn bg-danger text-white samuraimart-delete-submit-button">退会する</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection