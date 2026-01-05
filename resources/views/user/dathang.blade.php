@extends('layouts.frontend')
@section('title', 'Thanh toán')
@section('content')
@php
use Gloudemans\Shoppingcart\Facades\Cart;
@endphp

<div class="page-title-overlap bg-dark pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item">
                        <a class="text-nowrap" href="{{ route('frontend.home') }}"><i class="ci-home"></i>Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap">
                        <a href="{{ route('frontend.giohang.hienthi') }}">Giỏ hàng</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Thanh toán</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Thanh toán</h1>
        </div>
    </div>
</div>
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <section class="col-lg-8">
            <div class="steps steps-light pt-2 pb-3 mb-5">
                <a class="step-item active" href="{{ route('frontend.giohang.hienthi') }}">
                    <div class="step-progress"><span class="step-count">1</span></div>
                    <div class="step-label"><i class="ci-cart"></i>Giỏ hàng</div>
                </a>
                <a class="step-item active current" href="#">
                    <div class="step-progress"><span class="step-count">2</span></div>
                    <div class="step-label"><i class="ci-card"></i>Thanh toán</div>
                </a>
                <a class="step-item" href="#">
                    <div class="step-progress"><span class="step-count">3</span></div>
                    <div class="step-label"><i class="ci-check-circle"></i>Hoàn tất</div>
                </a>
            </div>
            <h2 class="h6 pt-1 pb-3 mb-3 border-bottom">Thông tin giao hàng</h2>
            <form id="payment-form" method="post" action="{{ route('user.dathang') }}" class="needs-validation" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="HoVaTen">Khách hàng</label>
                    <input class="form-control" type="text" id="HoVaTen" value="{{ Auth::user()->name ?? '' }}" disabled />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="dienthoaigiaohang">Điện thoại giao hàng</label>
                    <input class="form-control @error('dienthoaigiaohang') is-invalid @enderror" type="text" id="dienthoaigiaohang" name="dienthoaigiaohang" value="{{ $nguoidung->phone }}" required />
                    @error('dienthoaigiaohang')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="diachigiaohang">Địa chỉ giao hàng</label>
                    <input class="form-control @error('diachigiaohang') is-invalid @enderror" type="text" id="diachigiaohang" name="diachigiaohang" value="{{ $nguoidung->diachi }}" required />
                    @error('diachigiaohang')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phuongthucthanhtoan_id" class="form-label">Phương thức thanh toán</label>
                    <select id="phuongthucthanhtoan_id" name="phuongthucthanhtoan_id" class="form-select" required>
                        <option value="">-- Chọn phương thức thanh toán --</option>
                        @foreach($phuongthucthanhtoan as $pttt)
                        <option value="{{ $pttt->id }}">{{ $pttt->tenphuongthucthanhtoan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Hiển thị thông tin thanh toán online nếu cần -->
                <div id="payment-online-info" style="display:none;" class="mb-3 text-center">
                    <img src="{{ asset('/storage/app/thanh-toan-online.jpg') }}" alt="Thanh toán online" class="img-fluid mx-auto" style="width:300px;" />
                </div>

                <h6 class="mb-3 py-3 border-bottom">Thông tin xuất hóa đơn</h6>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" checked id="same-address">
                    <label class="form-check-label" for="same-address">Tương tự thông tin giao hàng</label>
                </div>
            </form>
            <!-- Navigation (desktop) -->
            <div class="d-none d-lg-flex pt-4 mt-3">
                <div class="w-50 pe-3">
                    <a class="btn btn-secondary d-block w-100" href="{{ route('frontend.giohang.hienthi') }}">
                        <i class="ci-arrow-left mt-sm-0 me-1"></i>
                        <span class="d-none d-sm-inline">Quay lại giỏ hàng</span>
                        <span class="d-inline d-sm-none">Quay lại</span>
                    </a>
                </div>
            <div class="w-50 ps-2">
                <button type="button" class="btn btn-primary d-block w-100" id="complete-order-btn">
                    <span class="d-none d-sm-inline">Hoàn tất đặt hàng</span>
                    <span class="d-inline d-sm-none">Hoàn tất</span>
                    <i class="ci-arrow-right mt-sm-0 ms-1"></i>
                </button>
            </div>
            </div>
        </section>
        <aside class="col-lg-4 pt-4 pt-lg-0 ps-xl-5">
            <div class="bg-white rounded-3 shadow-lg p-4 ms-lg-auto">
                <div class="py-2 px-xl-2">
                    <div class="widget mb-3">
                        <h2 class="widget-title text-center">Sản phẩm đã đặt</h2>
                        @foreach(Cart::content() as $value)
                        <div class="d-flex align-items-center pb-2 border-bottom">
                            <a class="d-block flex-shrink-0" href="#">
                                <img src="{{ asset('/storage/app/' . $value->options->image) }}" width="64" />
                            </a>
                            <div class="ps-2">
                                <h6 class="widget-product-title">{{ $value->name }}</h6>
                                <div class="widget-product-meta">
                                    <span class="text-accent me-2" style="color: green; font-weight: bold;">
                                        {{ number_format($value->price, 0, ',', '.') }}<small>đ</small>
                                    </span>
                                    <span class="text-muted">x {{ $value->qty }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <ul class="list-unstyled fs-sm pb-2 border-bottom">
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="me-2">Tổng tiền sản phẩm:</span>
                            <span class="text-end">{{ Cart::priceTotal(0, ',', '.') }}<small>đ</small></span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="me-2">Phí vận chuyển:</span>
                            <span class="text-end" id="shipping-fee">—</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="me-2">Thuế GTGT:</span>
                            <span class="text-end">{{ Cart::tax(0, ',', '.') }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="me-2">Giảm giá:</span>
                            <span class="text-end">—</span>
                        </li>
                    </ul>
                    @php
                    $tax = floatval(str_replace(['.', 'đ', ','], ['', '', '.'], Cart::tax(0, '', '')));
                    $productTotal = floatval(str_replace(['.', 'đ', ','], ['', '', '.'], Cart::priceTotal(0, '', '')));
                    $total = $productTotal + $tax;
                    @endphp
                    <h3 class="fw-normal text-center my-4">
                        Tổng tiền:<br>
                        <span id="order-total" data-total="{{ $total }}">{{ number_format($total, 0, ',', '.') }}</span><small>đ</small>
                    </h3>
                    <!-- ETH display removed -->
                </div>
            </div>
        </aside>
    </div>
    <!-- Navigation (mobile) -->
    <div class="row d-lg-none">
        <div class="col-lg-8">
            <div class="d-flex pt-4 mt-3">
                <div class="w-50 pe-3">
                    <a class="btn btn-secondary d-block w-100" href="{{ route('frontend.giohang.hienthi') }}">
                        <i class="ci-arrow-left mt-sm-0 me-1"></i>
                        <span class="d-none d-sm-inline">Quay lại giỏ hàng</span>
                        <span class="d-inline d-sm-none">Quay lại</span>
                    </a>
                </div>
                <div class="w-50 ps-2">
                    <label for="complete-order-btn" class="btn btn-primary d-block w-100">
                        <span class="d-none d-sm-inline">Hoàn tất đặt hàng</span>
                        <span class="d-inline d-sm-none">Hoàn tất</span>
                        <i class="ci-arrow-right mt-sm-0 ms-1"></i>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection