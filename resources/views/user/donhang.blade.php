@extends('layouts.frontend')
@section('title', 'Đơn Hàng Của Tôi')
@section('content')
<div class="page-title-overlap bg-dark pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item">
                        <a class="text-nowrap" href="{{ route('frontend.home') }}"><i class="ci-home"></i>Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap">
                        <a href="{{ route('user.home') }}">Khách hàng</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Lịch sử mua hàng</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Đơn hàng của tôi</h1>
        </div>
    </div>
</div>
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <aside class="col-lg-4 pt-4 pt-lg-0 pe-xl-5">
            <div class="bg-white rounded-3 shadow-lg pt-1 mb-5 mb-lg-0">
                <div class="d-md-flex justify-content-between align-items-center text-center text-md-start p-4">
                    <div class="d-md-flex align-items-center">
                        <div class="img-thumbnail rounded-circle position-relative flex-shrink-0 mx-auto mb-2 mx-md-0 mb-md-0" style="width:6.375rem;">
                            <img class="rounded-circle" src="{{ asset('public/img/avatar.jpg') }}" />
                        </div>
                        <div class="ps-md-3">
                            <h3 class="fs-base mb-0">{{ $nguoidung->name }}</h3>
                            <span class="text-accent fs-sm">{{ $nguoidung->email }}</span>
                        </div>
                    </div>
                    <a class="btn btn-primary d-lg-none mb-2 mt-3 mt-md-0" href="#account-menu" data-bs-toggle="collapse" aria-expanded="false">
                        <i class="ci-menu me-2"></i>Đơn hàng của tôi
                    </a>
                </div>
                <div class="d-lg-block collapse" id="account-menu">
                    <div class="bg-secondary px-4 py-3">
                        <h3 class="fs-sm mb-0 text-muted">Quản lý</h3>
                    </div>
                    <ul class="list-unstyled mb-0">
                        @if($nguoidung->DonHang->count() > 0)
                        <li class="border-bottom mb-0">
                            <a class="nav-link-style d-flex align-items-center px-4 py-3 active" href="{{ route('user.donhang') }}">
                                <i class="ci-bag opacity-60 me-2"></i>Đơn hàng<span class="fs-sm text-muted ms-auto">{{ $nguoidung->DonHang->count() }}</span>
                            </a>
                        </li>
                        @else
                        <li class="border-bottom mb-0">
                            <a class="nav-link-style d-flex align-items-center px-4 py-3 active" href="{{ route('user.donhang') }}">
                                <i class="ci-bag opacity-60 me-2"></i>Đơn hàng<span class="fs-sm text-muted ms-auto">0</span>
                            </a>
                        </li>
                        @endif
                        <li class="border-bottom mb-0">
                            <a class="nav-link-style d-flex align-items-center px-4 py-3" href="#">
                                <i class="ci-heart opacity-60 me-2"></i>Sản phẩm yêu thích<span class="fs-sm text-muted ms-auto">0</span>
                            </a>
                        </li>
                        <li class="mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3" href="#">
                                <i class="ci-star opacity-60 me-2"></i>Đánh giá sản phẩm<span class="fs-sm text-muted ms-auto">0</span>
                            </a>
                        </li>
                    </ul>
                    <div class="bg-secondary px-4 py-3">
                        <h3 class="fs-sm mb-0 text-muted">Thiết lập tài khoản</h3>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="border-bottom mb-0">
                            <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('user.home') }}">
                                <i class="ci-bag opacity-60 me-2"></i>Hồ sơ cá nhân
                            </a>
                        </li>
                        <li class="d-lg-none border-top mb-0">
                            <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('logout') }}">
                                <i class="ci-sign-out opacity-60 me-2"></i>Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        <section class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center pt-lg-2 pb-4 pb-lg-5 mb-lg-3">
                <div class="d-flex align-items-center">
                    <label class="d-none d-lg-block fs-sm text-light text-nowrap opacity-75 me-2" for="order-sort">Sắp xếp theo:</label>
                    <label class="d-lg-none fs-sm text-nowrap opacity-75 me-2" for="order-sort">Sắp xếp theo:</label>
                    <form action="{{ route('user.donhang') }}" method="GET">
                        <select class="form-select" id="order-sort" name="tinhtrang" onchange="this.form.submit()">
                            <option value="Tất cả" {{ request('tinhtrang') == 'Tất cả' ? 'selected' : '' }}>Tất cả</option>
                            <option value="Chờ xác nhận" {{ request('tinhtrang') == 'Chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="Đang vận chuyển" {{ request('tinhtrang') == 'Đang vận chuyển' ? 'selected' : '' }}>Đang vận chuyển</option>
                            <option value="Đã hoàn thành" {{ request('tinhtrang') == 'Đã hoàn thành' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="Đã hủy" {{ request('tinhtrang') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </form>
                </div>
                <a class="btn btn-primary btn-sm" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="ci-sign-out me-2"></i>Đăng xuất
                </a>
            </div>

            @if($donhangs->isEmpty())
            <div class="alert alert-info" role="alert">
                Bạn chưa có đơn hàng nào.
            </div>
            @else
            <div class="table-responsive fs-md mb-4">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt hàng</th>
                            <th>Trạng thái</th>
                            <th>Tổng tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donhangs as $dh)
                        <tr>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#ChiTietDonHang{{ $dh->id }}">
                                    {{ $dh->ma_don }}
                                </a>
                            </td>
                            <td>{{ $dh->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($dh->tinhtrang->tinhtrang == 'Chờ xác nhận')
                                <span class="text-info">{{ $dh->tinhtrang->tinhtrang }}</span>
                                @elseif($dh->tinhtrang->tinhtrang == 'Đang vận chuyển')
                                <span class="text-warning">{{ $dh->tinhtrang->tinhtrang }}</span>
                                @elseif($dh->tinhtrang->tinhtrang == 'Đã hoàn thành')
                                <span class="text-success">{{ $dh->tinhtrang->tinhtrang }}</span>
                                @elseif($dh->tinhtrang->tinhtrang == 'Đã hủy')
                                <span class="text-danger">{{ $dh->tinhtrang->tinhtrang }}</span>
                                @else
                                {{ $dh->tinhtrang->tinhtrang }}
                                @endif
                                <br/>
                                @if(isset($dh->refund_status))
                                    @if($dh->refund_status == 'pending')
                                        <span class="badge bg-warning text-dark">Hoàn trả đang chờ xử lý</span>
                                    @elseif($dh->refund_status == 'completed')
                                        <span class="badge bg-success">Đã hoàn trả</span>
                                    @elseif($dh->refund_status == 'failed')
                                        <span class="badge bg-danger">Hoàn trả thất bại</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                {{ number_format($dh->tongtien, 0, ',', '.') }} đ
                            </td>
                            <td>
                                @if($dh->tinhtrang->tinhtrang == 'Chờ xác nhận')
                                <button class="btn btn-secondary btn-sm" disabled>Đã nhận hàng</button>
                                @elseif($dh->tinhtrang->tinhtrang == 'Đang vận chuyển')
                                <form action="{{ route('user.donhang.nhanhang', $dh->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Đã nhận hàng</button>
                                </form>
                                @endif
                                @if($dh->tinhtrang->tinhtrang != 'Đã hủy' && $dh->tinhtrang->tinhtrang != 'Đã hoàn thành' && (!isset($dh->refund_status) || $dh->refund_status != 'completed'))
                                <form action="{{ route('user.donhang.huydon', $dh->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Hủy đơn</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            @if(session('warning'))
            <div class="alert alert-danger fs-base" role="alert">
                <i class="ci-close-circle me-2"></i>{{ session('warning') }}
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success fs-base" role="alert">
                <i class="ci-check-circle me-2"></i>{{ session('success') }}
            </div>
            @endif
            <form action="{{ route('user.hosocanhan') }}" method="post" class="needs-validation" novalidate>
                @csrf
                <!-- Form content here -->
            </form>
        </section>
    </div>
</div>

<!-- Modal Chi tiết đơn hàng -->
@foreach($donhangs as $dh)
<div class="modal fade" id="ChiTietDonHang{{ $dh->id }}" tabindex="-1" aria-labelledby="ChiTietDonHangLabel{{ $dh->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ChiTietDonHangLabel{{ $dh->id }}">Chi tiết đơn hàng {{ $dh->ma_don }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Ngày đặt: <b>{{ $dh->created_at->format('d/m/Y') }}</b></p>
                <p>Người nhận: <b>{{ $dh->nguoidung->name }}</b></p>
                <p>Điện thoại: <b>{{ $dh->dienthoaigiaohang }}</b></p>
                <p>Địa chỉ: <b>{{ $dh->diachigiaohang }}</b></p>
                <p>Phương thức thanh toán: <b>{{ $dh->phuongthucthanhtoan->tenphuongthucthanhtoan ?? 'N/A' }}</b></p>
                <h6>Chi tiết sản phẩm</h6>
                <ul>
                    <table class="table table-bordered table-hover table-sm mb-auto">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Hình ảnh</th>
                                <th>Sản phẩm</th>
                                <th width="5%">SL</th>
                                <th width="10%">Đơn giá</th>
                                <th width="15%">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dh->donhang_chitiet as $chitiet)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset('/storage/app/' . $chitiet->sanpham->hinhanh) }}" />
                                </td>
                                <td>{{ $chitiet->sanpham ? $chitiet->sanpham->tensanpham : 'N/A' }}</td>
                                <td>{{ $chitiet->soluongban }}</td>
                                <td class="text-end">{{ number_format($chitiet->dongiaban) }}<sup><u>đ</u></sup></td>
                                <td class="text-end">{{ number_format($chitiet->soluongban * $chitiet->dongiaban) }}<sup><u>đ</u></sup></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="5">Tổng thanh toán:</td>
                                <td class="text-end">
                                    @php
                                        $totalDetails = 0;
                                        foreach ($dh->donhang_chitiet as $item) {
                                            $totalDetails += $item->soluongban * $item->dongiaban;
                                        }
                                        $totalPayment = $totalDetails + ($dh->phivanchuyen ?? 0);
                                    @endphp
                                    <strong>{{ number_format($totalPayment) }}</strong><sup><u>đ</u></sup>
                                </td>
                            </tr>
                            <!-- ETH price removed -->
                        </tbody>
                    </table>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection