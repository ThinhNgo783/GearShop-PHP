@extends('layouts.app')
@section('title', 'Quản lý Phương Thức Thanh Toán')
@section('content')
<div class="card">
    <div class="card-header"><b>Quản lý Phương Thức Thanh Toán</b></div>
    <div class="card-body table-responsive">
        <p>
            <a href="{{ route('admin.thanhtoan.them') }}" class="btn btn-info"><i class="fa-light fa-plus"></i>Thêm Phương Thức Thanh Toán</a>
        </p>
        <table class="table table-bordered table-hover table-sm mb-0">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="30%">Tên phương thức</th>
                    <th width="20%">Trạng thái</th>
                    <th width="25%">Hình ảnh</th>
                    <th width="10%">Sửa</th>
                    <th width="10%">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($phuongthucThanhToan as $pttt)
                <tr>
                    <td class="align-middle text-center">{{ $pttt->id }}</td>
                    <td class="align-middle text-center">{{ $pttt->tenphuongthucthanhtoan }}</td>
                    <td class="align-middle text-center">{{ $pttt->hoatdong ? 'Kích hoạt' : 'Không kích hoạt' }}</td>
                    <td class="text-center"><img src="{{ asset('storage/app/' . $pttt->hinhanh) }}" width="80" class="img-thumbnail" /></td>
                    <td class="align-middle text-center"><a href="{{ route('admin.thanhtoan.sua', ['id' => $pttt->id]) }}"><i class="fa-light fa-edit"></i></a></td>
                    <td class="align-middle text-center"><a href="{{ route('admin.thanhtoan.xoa', ['id' => $pttt->id]) }}" onclick="return confirm('Bạn có muốn xóa sản phẩm {{ $pttt->tenphuongthucthanhtoan }} không?')"><i class="fa-light fa-trash-alt text-danger"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection