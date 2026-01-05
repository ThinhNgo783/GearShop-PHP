@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><b>Trang chủ</b></div>
    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Sản phẩm</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $sanphamCount }}</h5>
                        <p class="card-text">Tổng số sản phẩm</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Số lượng sản phẩm</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $sanphamConLaiCount }}</h5>
                        <p class="card-text">Số lượng sản phẩm còn lại</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Đơn hàng</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $donhangCount }}</h5>
                        <p class="card-text">Tổng số đơn hàng</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Khách hàng</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $nguoidungCount }}</h5>
                        <p class="card-text">Tổng số khách hàng</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Sản phẩm bán chạy</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($banchay as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $item->sanpham->tensanpham }}
                                <span class="badge bg-primary rounded-pill"> đã bán {{ $item->total }} sản phẩm</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection