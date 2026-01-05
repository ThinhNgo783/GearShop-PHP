@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><b>Sản phẩm</b></div>
    <div class="card-body table-responsive">
        <form action="{{ route('admin.sanpham') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm" value="{{ request()->search }}">

                </div>
                <div class="col-md-auto">
                    <select name="hangsanxuat_id" class="form-control">
                        <option value="">Chọn hãng sản xuất</option>
                        @foreach($hangsanxuat as $hsx)
                        <option value="{{ $hsx->id }}" {{ request()->hangsanxuat_id == $hsx->id ? 'selected' : '' }}>{{ $hsx->tenhang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary"><i class="fa-light fa-search"></i> Tìm kiếm </button>
                </div>
            </div>
        </form>
        <p>
            <a href="{{ route('admin.sanpham.them') }}" class="btn btn-info"><i class="fa-light fa-plus"></i> Thêm mới </a>
            <a href="#nhap" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importModal"><i class="fa-light fa-upload"></i> Nhập từ Excel</a>
            <a href="{{ route('admin.sanpham.xuat') }}" class="btn btn-success"><i class="fa-light fa-download"></i> Xuất ra Excel </a>
        </p>
        <p>{{ $sanpham->appends(request()->input())->links() }}</p>
        <table class="table table-bordered table-hover table-sm mb-auto">
            <thead>
                <tr>
                    <th width="3%">#</th>
                    <th width="10%">Hình ảnh</th>
                    <th width="10%">Loại sản phẩm</th>
                    <th width="5%">HSX</th>
                    <th width="10%">Tên sản phẩm</th>
                    <th width="5%">SL</th>
                    <th width="5%">Đơn giá</th>
                    <th width="5%">Giá nhập</th>
                    <th width="20%">Mô tả</th>
                    <th width="5%">Sửa</th>
                    <th width="5%">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sanpham as $value)
                <tr>
                    <td class="align-middle text-center">{{ $sanpham->firstItem() + $loop->index}}</td>
                    <td class="text-center"><img src="{{ asset('/storage/app/' . $value->hinhanh) }}" width="100" class="img-thumbnail" /></td>
                    <td class="align-middle text-center">{{ $value->LoaiSanPham->tenloai }}</td>
                    <td class="align-middle text-center">{{ $value->HangSanXuat->tenhang }}</td>
                    <td class="align-middle text-center">{{ $value->tensanpham }}</td>
                    <td class="align-middle text-center">{{ $value->soluong }}</td>
                    <td class="align-middle text-center">{{ number_format($value->dongia) }}</td>
                    <td class="align-middle text-center">{{ number_format($value->gianhap) }}</td>
                    <td class="align-middle">{{ strip_tags($value->motasanpham) }}</td>
                    <td class="align-middle text-center"><a href="{{ route('admin.sanpham.sua', ['id' => $value->id]) }}"><i class="fa-light fa-edit"></i></a></td>
                    <td class="align-middle text-center"><a href="{{ route('admin.sanpham.xoa', ['id' => $value->id]) }}" onclick="return confirm('Bạn có muốn xóa sản phẩm {{ $value->tensanpham }} không?')"><i class="fa-light fa-trash-alt text-danger"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $sanpham->appends(request()->input())->links() }}
        </div>
    </div>
</div>
<form action="{{ route('admin.sanpham.nhap') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Nhập từ Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-0">
                        <label for="file_excel" class="form-label">Chọn tập tin Excel</label>
                        <input type="file" class="form-control" id="file_excel" name="file_excel" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-light fa-times"></i> Hủy bỏ</button>
                    <button type="submit" class="btn btn-danger"><i class="fa-light fa-upload"></i> Nhập dữ liệu</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection