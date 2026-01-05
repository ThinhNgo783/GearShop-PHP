@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><b>Danh sách đơn hàng</b></div>
    <div class="card-body table-responsive">
        <p><a href="{{ route('admin.donhang.them') }}" class="btn btn-info"><i class="fa-light fa-plus"></i> Thêm mới</a></p>
        @foreach($donhang as $dh)
        <div class="card mb-3">
            <div class="card-header">
                <b>Đơn hàng #{{ $loop->iteration }}</b>
                <span class="float-end">Mã đơn: {{ $dh->ma_don }}</span>
            </div>
            <div class="card-body">
                <p>Khách hàng: <strong>{{ $dh->nguoidung->name }}</strong></p>
                <p>Điện thoại: <strong>{{ $dh->dienthoaigiaohang }}</strong></p>
                <p>Địa chỉ: <strong>{{ $dh->diachigiaohang }}</strong></p>
                <p>Ngày đặt: <strong>{{ $dh->created_at->format('d/m/Y H:i:s') }}</strong></p>
                <p>Phương thức thanh toán: <strong>{{ $dh->phuongthucthanhtoan->tenphuongthucthanhtoan ?? 'N/A'}}</strong></p>
                <p>Tình trạng:
                    <strong>{{ $dh->tinhtrang->tinhtrang }}</strong>
                </p>
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Sản phẩm</th>
                            <th width="5%">SL</th>
                            <th width="15%">Đơn giá</th>
                            <th width="20%">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $tongtien = 0; @endphp
                        @foreach($dh->donhang_chitiet as $chitiet)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $chitiet->sanpham ? $chitiet->sanpham->tensanpham : 'N/A' }}</td>
                            <td>{{ $chitiet->soluongban }}</td>
                            <td class="text-end">{{ number_format($chitiet->dongiaban) }}<sup><u>đ</u></sup></td>
                            <td class="text-end">{{ number_format($chitiet->soluongban * $chitiet->dongiaban) }}<sup><u>đ</u></sup></td>
                        </tr>
                        @php $tongtien += $chitiet->soluongban * $chitiet->dongiaban; @endphp
                        @endforeach
                        <tr>
                            <td colspan="4">Tổng tiền sản phẩm:</td>
                            <td class="text-end"><strong>{{ number_format($tongtien) }}</strong><sup><u>đ</u></sup></td>
                        </tr>
                        <tr>
                            <td colspan="4">Phí vận chuyển:</td>
                            <td class="text-end"><strong>{{ number_format($dh->phivanchuyen ?? 0) }}</strong><sup><u>đ</u></sup></td>
                        </tr>
                        <tr>
                            <td colspan="4">Tổng thanh toán:</td>
                            <td class="text-end"><strong>{{ number_format($tongtien + ($dh->phivanchuyen ?? 0)) }}</strong><sup><u>đ</u></sup></td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-3">
                    <a href="{{ route('admin.donhang.sua', ['id' => $dh->id]) }}" class="btn btn-warning btn-sm"><i class="fa-light fa-edit"></i> Sửa</a>
                    <a href="{{ route('admin.donhang.xoa', ['id' => $dh->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có muốn xóa đơn hàng của khách hàng {{ $dh->nguoidung->name }} không?')"><i class="fa-light fa-trash-alt"></i> Xóa</a>
                    <a href="{{ route('admin.donhang.export_pdf', ['id' => $dh->id]) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="fa-light fa-file-pdf"></i> Xuất hóa đơn
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
