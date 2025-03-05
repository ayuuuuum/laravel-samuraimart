@extends('layouts.app')

@section('content')
<div class="container pt-2">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('mypage') }}">マイページ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">パスワード変更</li>
                </ol>
            </nav>

            <h1 class="mb-3">パスワード変更</h1>

            <hr class="mb-4">

            <form method="post" action="{{ route('mypage.update_password') }}">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">

                <div class="form-group row mb-3">
                    <label for="password" class="col-md-5 col-form-label text-md-left fw-medium">新しいパスワード</label>

                    <div class="col-md-7">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror samuraimart-login-input" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label for="password-confirm" class="col-md-5 col-form-label text-md-left fw-medium">新しいパスワード（確認用）</label>

                    <div class="col-md-7">
                        <input id="password-confirm" type="password" class="form-control samuraimart-login-input" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <hr class="mb-4">

                <button type="submit" class="btn samuraimart-submit-button w-100 text-white">
                    更新
                </button>
            </form>
        </div>
    </div>
</div>
@endsection