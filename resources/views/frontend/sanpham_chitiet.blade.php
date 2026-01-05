@extends('layouts.frontend')
@section('title', $sanpham->tensanpham)
@section('content')
<!-- Breadcrumbs -->
<div class="page-title-overlap bg-dark pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item">
                        <a class="text-nowrap" href="{{ route('frontend.home') }}"><i class="ci-home"></i>Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap">
                        <a href="{{ route('frontend.sanpham') }}">Sản phẩm</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Chi tiết</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">{{ $sanpham->tensanpham }}</h1>
        </div>
    </div>
</div>

<!-- Product Details -->
<div class="container">
    <div class="bg-light shadow-lg rounded-3 px-4 py-3 mb-5">
        <div class="px-lg-3">
            <div class="row">
                <!-- Product Image -->
                <div class="col-lg-7 pe-lg-0 pt-lg-4">
                    <div class="product-gallery">
                        <div class="product-gallery-preview order-sm-2">
                            <div class="product-gallery-preview-item active">
                                <img class="image-zoom" src="{{ asset('storage/app/' . $sanpham->hinhanh) }}" data-zoom="{{ asset('storage/app/' . $sanpham->hinhanh) }}" width="300" />
                                <div class="image-zoom-pane"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-lg-5 pt-4 pt-lg-0">
                    <div class="product-details ms-auto pb-3">
                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <a href="#reviews" data-scroll>
                                <div class="star-rating">
                                    <i class="star-rating-icon ci-star-filled active"></i>
                                    <i class="star-rating-icon ci-star-filled active"></i>
                                    <i class="star-rating-icon ci-star-filled active"></i>
                                    <i class="star-rating-icon ci-star-filled active"></i>
                                    <i class="star-rating-icon ci-star"></i>
                                </div>
                                <span class="d-inline-block fs-sm text-body align-middle mt-1 ms-1">74 đánh giá</span>
                            </a>
                        </div>

                        <!-- Product Price -->
                        <div class="mb-3">
                            <span class="h3 text-accent me-1">{{ number_format($sanpham->dongia, 0, ',', '.') }}<small>đ</small></span>
                            <div class="float-end">
                                @auth
                                <button id="favoriteBtn" class="btn btn-link p-0" title="Yêu thích" data-favorited="{{ $isFavorited ? '1' : '0' }}">
                                    @if(!empty($isFavorited))
                                        <i class="ci-heart-fill text-danger" id="favoriteIcon" style="font-size:1.4rem;"></i>
                                    @else
                                        <i class="ci-heart" id="favoriteIcon" style="font-size:1.4rem;"></i>
                                    @endif
                                </button>
                                @else
                                <a href="{{ route('user.dangnhap') }}" class="btn btn-link p-0" title="Đăng nhập để yêu thích"><i class="ci-heart" style="font-size:1.4rem;"></i></a>
                                @endauth
                            </div>
                        </div>

                        <!-- Quantity and Add to Cart -->
                        @php $inStock = intval($sanpham->soluong) > 0; @endphp
                        <form action="{{ route('frontend.giohang.them', ['tenloai_slug' => $sanpham->loaiSanPham->tenloai_slug, 'tensanpham_slug' => $sanpham->tensanpham_slug]) }}" method="post">
                            @csrf
                            <div class="mb-2">
                                <strong>Tình trạng:</strong>
                                @if($inStock)
                                    <span class="text-success">Còn {{ $sanpham->soluong }} sản phẩm</span>
                                @else
                                    <span class="text-danger">Hết hàng</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="quantity">Số lượng</label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" id="decreaseQuantity" {{ $inStock ? '' : 'disabled' }}>-</button>
                                    <input class="form-control text-center" type="number" id="quantity" name="quantity" min="1" max="{{ $sanpham->soluong }}" value="{{ $inStock ? 1 : 0 }}" style="width: 5rem;" {{ $inStock ? '' : 'disabled' }} />
                                    <button type="button" class="btn btn-outline-secondary" id="increaseQuantity" {{ $inStock ? '' : 'disabled' }}>+</button>
                                </div>
                            </div>
                            <button class="btn {{ $inStock ? 'btn-primary' : 'btn-secondary' }} btn-sm d-block w-100 mb-2" type="submit" {{ $inStock ? '' : 'disabled' }}>
                                <i class="ci-cart fs-sm me-1"></i>{{ $inStock ? 'Thêm vào giỏ hàng' : 'Hết hàng' }}
                            </button>
                        </form>

                        <!-- Product Information Accordion -->
                        <div class="accordion mb-4" id="productPanels">
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <a class="accordion-button" href="#productInfo" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="productInfo">
                                        <i class="ci-announcement text-muted fs-lg align-middle mt-n1 me-2"></i>Thông tin cơ bản
                                    </a>
                                </h3>
                                <div class="accordion-collapse collapse show" id="productInfo" data-bs-parent="#productPanels">
                                    <div class="accordion-body">
                                        <b> {{ strip_tags($sanpham->motasanpham) }} </b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                               
                </div>
            </div>
        </div>
    </div>
 </div>

    <!-- Reviews -->
    <section class="container mt-4" id="reviews">
        <h3 class="h5 mb-3">Đánh giá khách hàng</h3>

        @php
            $average = isset($reviews) ? ($reviews->avg('rating') ?: 0) : 0;
            $countReviews = isset($reviews) ? $reviews->count() : 0;
        @endphp

        <div class="mb-3">
            <strong>Điểm trung bình:</strong>
            <span class="text-accent">{{ number_format($average,1) }} / 5</span>
            <small class="text-muted">({{ $countReviews }} đánh giá)</small>
        </div>

        @if($countReviews > 0)
            @foreach($reviews as $r)
            <div class="card mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div><strong>{{ $r->nguoidung ? $r->nguoidung->name : 'Khách' }}</strong></div>
                        <div>
                            @for($i=0;$i<5;$i++)
                                @if($i < $r->rating)
                                    <i class="ci-star-filled text-warning"></i>
                                @else
                                    <i class="ci-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="mt-2">{{ $r->comment }}</div>
                    <div class="text-muted small mt-1">{{ $r->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            @endforeach
        @else
            <div class="alert alert-info">Chưa có đánh giá cho sản phẩm này.</div>
        @endif

        @auth
        <div class="card mt-3">
            <div class="card-body">
                <h5>Gửi đánh giá</h5>
                <form action="{{ route('frontend.sanpham.review', ['tenloai_slug' => $loai->tenloai_slug, 'tensanpham_slug' => $sanpham->tensanpham_slug]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="rating" class="form-label">Điểm</label>
                        <select id="rating" name="rating" class="form-select" required>
                            <option value="5">5 - Tuyệt vời</option>
                            <option value="4">4 - Tốt</option>
                            <option value="3">3 - Trung bình</option>
                            <option value="2">2 - Kém</option>
                            <option value="1">1 - Rất kém</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Bình luận</label>
                        <textarea id="comment" name="comment" rows="4" class="form-control"></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">Gửi đánh giá</button>
                </form>
            </div>
        </div>
        @else
        <div class="alert alert-secondary mt-3">Vui lòng <a href="{{ route('user.dangnhap') }}">đăng nhập</a> để đánh giá sản phẩm.</div>
        @endauth
    </section>

 <!-- Related products (carousel) -->
@if(isset($sanphamcungloai) && $sanphamcungloai->count() > 0)
<section class="container mt-4">
    <h3 class="h5 mb-3">Sản phẩm liên quan</h3>

    @php $chunks = $sanphamcungloai->chunk(4); @endphp
    <div id="relatedCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($chunks as $index => $chunk)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <div class="row">
                    @foreach($chunk as $sp)
                    <div class="col-6 col-md-3 mb-3">
                        <div class="card product-card h-100">
                            <a class="card-img-top d-block overflow-hidden" href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaiSanPham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                                <img src="{{ asset('storage/app/' . $sp->hinhanh) }}" class="img-fluid" />
                            </a>
                            <div class="card-body py-2">
                                <h3 class="product-title fs-sm mb-1">
                                    <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaiSanPham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">{{ Str::limit($sp->tensanpham, 40) }}</a>
                                </h3>
                                <div class="product-price">
                                    <span class="text-accent">{{ number_format($sp->dongia, 0, ',', '.') }}<small>đ</small></span>
                                </div>
                                @php $spInStock = intval($sp->soluong) > 0; @endphp
                                <form action="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sp->tensanpham_slug]) }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm {{ $spInStock ? 'btn-primary' : 'btn-secondary' }} w-100" {{ $spInStock ? '' : 'disabled' }}>
                                        <i class="ci-cart me-1"></i>{{ $spInStock ? 'Thêm vào giỏ' : 'Hết hàng' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @if($chunks->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#relatedCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#relatedCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
</section>
@endif
@endsection

@section('scripts')
<script>
    (function(){
        var decreaseBtn = document.getElementById('decreaseQuantity');
        var increaseBtn = document.getElementById('increaseQuantity');
        var quantityInput = document.getElementById('quantity');
        if (!quantityInput) return;

        function toInt(v){ return parseInt(v) || 0; }

        if (decreaseBtn) {
            decreaseBtn.addEventListener('click', function() {
                var currentValue = toInt(quantityInput.value);
                if (currentValue > 1) quantityInput.value = currentValue - 1;
            });
        }

        if (increaseBtn) {
            increaseBtn.addEventListener('click', function() {
                var currentValue = toInt(quantityInput.value);
                var max = toInt(quantityInput.getAttribute('max')) || 0;
                if (max > 0) {
                    if (currentValue < max) quantityInput.value = currentValue + 1;
                } else {
                    quantityInput.value = currentValue + 1;
                }
            });
        }

        // Prevent manual input exceeding bounds
        quantityInput.addEventListener('input', function(){
            var val = toInt(quantityInput.value);
            var max = toInt(quantityInput.getAttribute('max')) || 0;
            if (val < 1) quantityInput.value = 1;
            if (max > 0 && val > max) quantityInput.value = max;
        });
    })();
</script>
@endsection

@section('inline-scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const favBtn = document.getElementById('favoriteBtn');
    if (!favBtn) return;

    favBtn.addEventListener('click', async function(e){
        e.preventDefault();
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const isFavorited = favBtn.getAttribute('data-favorited') === '1';
        const url = "{{ route('frontend.sanpham.favorite', ['tenloai_slug' => $loai->tenloai_slug, 'tensanpham_slug' => $sanpham->tensanpham_slug]) }}";

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            });

            if (!res.ok) throw new Error('HTTP error ' + res.status);
            const data = await res.json().catch(()=>null);

            // Toggle UI
            const icon = document.getElementById('favoriteIcon');
            if (isFavorited) {
                // Was favorited, now removed
                favBtn.setAttribute('data-favorited', '0');
                if (icon) {
                    icon.classList.remove('ci-heart-fill', 'text-danger');
                    icon.classList.add('ci-heart');
                }
            } else {
                favBtn.setAttribute('data-favorited', '1');
                if (icon) {
                    icon.classList.remove('ci-heart');
                    icon.classList.add('ci-heart-fill', 'text-danger');
                }
            }

            // optional: show flash message
            if (data && data.message) {
                // create temporary alert
                const alertBox = document.createElement('div');
                alertBox.className = 'alert alert-success position-fixed';
                alertBox.style.top = '20px';
                alertBox.style.right = '20px';
                alertBox.style.zIndex = 2000;
                alertBox.innerText = data.message;
                document.body.appendChild(alertBox);
                setTimeout(()=> alertBox.remove(), 2500);
            }

        } catch (err) {
            console.error(err);
            window.location.href = "{{ route('user.dangnhap') }}";
        }
    });
});
</script>
@endsection