@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header"><b>Loại Sản Phẩm</b></div>
            <div class="card-body table-responsive">
                <p><a href="{{ route('admin.loaisanpham.them') }}" class="btn btn-info"><i class="fa-light fa-plus"></i> Thêm mới</a></p>
                    <table class="table table-bordered table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="45%">Tên loại</th>
                            <th width="40%">Tên loại không dấu</th>
                            <th width="5%">Sửa</th>
                            <th width="5%">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loaisanpham as $value)
                            <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->tenloai }}</td>
                            <td>{{ $value->tenloai_slug }}</td>
                            <td class="text-center"><a href="{{ route('admin.loaisanpham.sua', ['id' => $value->id]) }}"><i class="fa-light fa-edit"></i></a></td>
                            <td class="text-center"><a href="{{ route('admin.loaisanpham.xoa', ['id' => $value->id]) }}" onclick="return confirm('Bạn có muốn xóa loại sản phẩm {{ $value->tenloai }} không?')"><i class="fa-light fa-trash-alt text-danger"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection