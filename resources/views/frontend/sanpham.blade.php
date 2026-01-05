@extends('layouts.frontend')

@section('title', 'Sản phẩm')

@section('content')
<div class="bg-dark pt-4">
    <div class="container pt-2 pb-3 pt-lg-3 pb-lg-4">
        <div class="d-lg-flex justify-content-between pb-3">
            <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                        <li class="breadcrumb-item">
                            <a class="text-nowrap" href="{{ route('frontend.home') }}"><i class="ci-home"></i>Trang chủ</a>
                        </div>
                <h1 class="h3 text-light mb-0">{{ $currentLoai }}</h1>
            </div>
        </div>
    </div>
</div>

<!-- Products -->
<div class="container pt-2 pb-3 pt-lg-3 pb-lg-4">
    <div class="row isotope-grid">
        @foreach($sanpham as $sp)
        <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{ $sp->loaisanpham ? $sp->loaisanpham->tenloai_slug : 'default-class' }}">
            <div class="card product-card h-100">
                <a class="card-img-top d-block overflow-hidden"
                    href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' =>  $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                    <img src="{{ asset('/storage/app/' . $sp->hinhanh) }}" />
                    {{ $sp->hangsanxuat? $sp->hangsanxuat->tenhang : 'Không có thương hiệu' }}
                </a>
                <div class="card-body py-2">
                    <h3 class="product-title fs-sm">
                        <a href="{{  route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                            {{ $sp->tensanpham }}</a>
                    </h3>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="product-price">
                            <span class="text-accent">{{ number_format($sp->dongia, 0, ',', '.') }}<small>đ</small></span>
                            @if(isset($ethPrice) && $ethPrice)
                            <br>
                            <small class="text-muted" style="color: green; font-weight: bold;">~{{ number_format($sp->dongia / $ethPrice, 6) }} <span style="font-family: Arial, sans-serif;">ETH</span>
                            </small>
                            @endif
                        </div>
                        <div class="star-rating">
                            <i class="star-rating-icon ci-star-filled active"></i>
                            <i class="star-rating-icon ci-star-filled active"></i>
                            <i class="star-rating-icon ci-star-filled active"></i>
                            <i class="star-rating-icon ci-star-filled active"></i>
                            <i class="star-rating-icon ci-star"></i>
                        </div>
                    </div>
                    <div class="card-body card-body-hidden mt-auto">
                        <form action="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sp->tensanpham_slug]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-sm d-block w-100 mb-2">
                                <i class="ci-cart fs-sm me-1"></i>Thêm vào giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- JavaScript for Filtering -->
<script>
    function filterProducts(category) {
        var items = document.querySelectorAll('.isotope-item');

        if (category === 'all') {
            items.forEach(function(item) {
                item.style.display = 'block'; // Show all products
            });
        } else {
            items.forEach(function(item) {
                if (item.classList.contains(category)) {
                    item.style.display = 'block'; // Show items that belong to the selected category
                } else {
                    item.style.display = 'none'; // Hide other items
                }
            });
        }
    }
</script>

<hr class="my-3">

<nav class="d-flex justify-content-between pt-2 mb-2" aria-label="Page navigation">
    <ul class="pagination">
        <!-- Nút Previous -->
        <li class="page-item {{ $sanpham->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $sanpham->previousPageUrl() }}">
                <i class="ci-arrow-left me-2"></i>Phía sau
            </a>
        </li>
    </ul>

    <ul class="pagination">
        <!-- Các số trang -->
        @foreach ($sanpham->getUrlRange(1, $sanpham->lastPage()) as $page => $url)
        <li class="page-item {{ $page == $sanpham->currentPage() ? 'active' : '' }}">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endforeach
    </ul>

    <ul class="pagination">
        <!-- Nút Next -->
        <li class="page-item {{ $sanpham->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $sanpham->nextPageUrl() }}" aria-label="Next">
                Tiếp tục<i class="ci-arrow-right ms-2"></i>
            </a>
        </li>
    </ul>
</nav>
<a class="btn-scroll-top" href="#top" data-scroll>
    <i class="btn-scroll-top-icon ci-arrow-up"></i>
</a>
@endsection