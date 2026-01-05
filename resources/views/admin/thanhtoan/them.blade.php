@extends('layouts.app')
@section('title', 'Thêm Phương Thức Thanh Toán')
@section('content')
<div class="card">
    <div class="card-header"><b>Thêm Phương Thức Thanh Toán</b></div>
    <div class="card-body">
        <form action="{{ route('admin.thanhtoan.them') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3">
                <label for="tenphuongthucthanhtoan" class="form-label">Tên phương thức</label>
                <input type="text" name="tenphuongthucthanhtoan" id="tenphuongthucthanhtoan" class="form-control" placeholder="Ví dụ: COD, Thanh toán Online" required>
                @error('tenphuongthucthanhtoan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="hoatdong" class="form-label">Trạng thái</label>
                <select name="hoatdong" id="hoatdong" class="form-select" required>
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="1">Kích hoạt</option>
                    <option value="0">Không kích hoạt</option>
                </select>
                @error('hoatdong')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="hinhanh" class="form-label">Hình ảnh</label>
                <input type="file" name="hinhanh" id="hinhanh" class="form-control" accept="image/*" required>
                @error('hinhanh')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-light fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
@endsection