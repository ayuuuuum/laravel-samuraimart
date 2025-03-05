@extends('layouts.app')

@section('content')
<div class="container pt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('mypage') }}">マイページ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">注文履歴</li>
                </ol>
            </nav>

            <h1 class="mb-4">注文履歴 {{ number_format($billings->total()) }}件</h1>

            <table class="table mb-4">
                <thead>
                    <tr>
                        <th scope="col">注文番号</th>
                        <th scope="col">購入日時</th>
                        <th scope="col">合計金額</th>
                        <th scope="col">詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($billings as $billing)
                        <tr>
                            <td>{{ $billing['code'] }}</td>
                            <td>{{ $billing['created_at']}}</td>
                            <td>￥{{ number_format($billing['total']) }}</td>
                            <td>
                                <a href="{{ route('mypage.cart_history_show', $billing['id']) }}">
                                    詳細
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $billings->links() }}
        </div>
    </div>
</div>

@endsection