@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h1 class="mb-3">パスワード再設定</h3>

            <p class="lh-lg">
                ご登録時のメールアドレスを入力してください。<br>
                パスワード再発行用のメールをお送りします。
            </p>

            <hr class="mb-4">

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif


            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group mb-4">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror samuraimart-login-input" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="メールアドレス">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>メールアドレスが正しくない可能性があります。</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn samuraimart-submit-button w-100 text-white">
                        送信
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection