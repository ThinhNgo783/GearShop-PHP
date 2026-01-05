@extends('layouts.frontend')

@section('title', 'Kết quả tìm kiếm')

@section('content')
<div class="container pt-4 pb-4">
    <h1 class="h3 mb-4">Kết quả tìm kiếm</h1>
    @if($sanpham->isEmpty())
    <p>Không tìm thấy sản phẩm nào phù hợp với từ khóa "{{ request()->input('query') }}".</p>
    @else
    <div class="row">
        <p>{{ $sanpham->appends(request()->input())->links() }}</p>
        @foreach($sanpham as $sp)
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card product-card">
                <a class="card-img-top d-block overflow-hidden"
                    href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                    <img src="{{ asset('storage/app/' . $sp->hinhanh) }}" alt="{{ $sp->tensanpham }}">
                </a>
                <div class="card-body py-2">
                    <h3 class="product-title fs-sm">
                        <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                            {{ $sp->tensanpham }}
                        </a>
                    </h3>
                    <div class="d-flex justify-content-between">
                        <div class="product-price">
                            <span class="text-accent">{{ number_format($sp->dongia, 0, ',', '.') }}<small>đ</small></span>
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
                    <a class="btn btn-primary btn-sm d-block w-100 mb-2" href="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sp->tensanpham_slug]) }}" method="POST">
                        <i class="ci-cart fs-sm me-1"></i>Thêm vào giỏ hàng
                    </a>
                </div>
            </div>
        </div>
        @endforeach
        <div class="mt-4">
            {{ $sanpham->appends(request()->input())->links() }}
        </div>
    </div>
    @endif
</div>
@endsection