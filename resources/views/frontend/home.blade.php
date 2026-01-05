@extends('layouts.frontend')

@section('title', 'Trang chủ')

@section('content')
<section class="container mt-4 mb-grid-gutter">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <a href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => 'phu-kien']) }}">
                    <img src="{{ asset('public/img/sanpham0.jpg') }}" class="d-block w-100" alt="Slide 1">
                </a>
            </div>
            <div class="carousel-item">
                <a href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => 'laptop']) }}">
                    <img src="{{ asset('public/img/sanpham1.jpg') }}" class="d-block w-100" alt="Slide 2">
                </a>
            </div>
            <div class="carousel-item">
                <a href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => 'laptop']) }}">
                    <img src="{{ asset('public/img/sanpham2.jpg') }}" class="d-block w-100" alt="Slide 3">
                </a>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

@foreach($loaisanpham as $lsp)
<section class="container pt-3 pb-2">
    <div class="d-flex flex-wrap justify-content-between align-items-center pt-1 border-bottom pb-4 mb-4">
        <h2 class="h3 mb-0 pt-3 me-2">{{ $lsp->tenloai }}</h2>
        <div class="pt-3">
            <a class="btn btn-outline-accent btn-sm" href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => $lsp->tenloai_slug]) }}">
                Xem tất cả<i class="ci-arrow-right ms-1 me-n1"></i>
            </a>
        </div>
    </div>
    <div class="row pt-2 mx-n2">
        @foreach($lsp->sanphams->take(8) as $sp)
        <div class="col-lg-3 col-md-4 col-sm-6 px-2 mb-4">
            <div class="card product-card">
                <a class="card-img-top d-block overflow-hidden" href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $lsp->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                    <img src="{{ asset('storage/app/' . $sp->hinhanh) }}" />
                </a>
                <div class="card-body py-2">
                    <a class="card-img-top d-block overflow-hidden" href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => $lsp->tenloai_slug]) }}">
                        {{ $lsp->tenloai }}</a>
                    <h3 class="product-title fs-sm">
                        <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $lsp->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                            {{ $sp->tensanpham }}</a>
                    </h3>
                    <div class="d-flex justify-content-between">
                    <div class="product-price">
                        <span class="text-accent">{{ number_format($sp->dongia, 0, ',', '.') }}<small>đ</small></span>
                    </div>
                        </div>
                        <div class="star-rating">
                            <i class="star-rating-icon ci-star-filled active"></i>
                            <i class="star-rating-icon ci-star-filled active"></i>
                            <i class="star-rating-icon ci-star-filled active"></i>
                            <i class="star-rating-icon ci-star-filled active"></i>
                            <i class="star-rating-icon ci-star"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body card-body-hidden">
                    <form action="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sp->tensanpham_slug]) }}" method="post">
                        @csrf
                        <button class="btn btn-primary btn-sm d-block w-100 mb-2" type="submit">
                            <i class="ci-cart fs-sm me-1"></i>Thêm vào giỏ hàng
                        </button>
                    </form>
                </div>
            </div>
            <hr class="d-sm-none">
        </div>
        @endforeach
    </div>
</section>
@endforeach
@endsection