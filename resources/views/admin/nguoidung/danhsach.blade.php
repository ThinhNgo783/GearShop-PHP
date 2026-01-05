@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><b>Người dùng</b></div>
    <div class="card-body table-responsive">
        <p><a href="{{ route('admin.nguoidung.them') }}" class="btn btn-info"><i class="fa-light fa-plus"></i> Thêm mới</a></p>
        <table class="table table-bordered table-hover table-sm mb-0">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Họ và tên</th>
                    <th width="15%">Tên đăng nhập</th>
                    <th width="20%">Email</th>
                    <th width="10%">Quyền hạn</th>
                    <th width="10%">Số điện thoại</th>
                    <th width="15%">Địa chỉ</th>
                    <th width="5%">Sửa</th>
                    <th width="5%">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nguoidung as $value)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->username }}</td>
                    <td>{{ $value->email }}</td>
                    <td>{{ $value->role }}</td>
                    <td>{{ $value->phone }}</td>
                    <td>
                        <strong>Địa chỉ:</strong> {{ $value->diachi ?? 'Chưa cập nhật' }}
                    </td>
                    <td class="text-center"><a href="{{ route('admin.nguoidung.sua', ['id' => $value->id]) }}"><i class="fa-light fa-edit"></i></a></td>
                    <td class="text-center"><a href="{{ route('admin.nguoidung.xoa', ['id' => $value->id]) }}" onclick="return confirm('Bạn có muốn xóa người dùng {{ $value->name }} không?')"><i class="fa-light fa-trash-alt text-danger"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection