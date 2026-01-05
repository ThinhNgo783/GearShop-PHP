@extends('layouts.app')
@section('title', 'Sửa Phương Thức Thanh Toán')
@section('content')
<div class="card">
    <div class="card-header"><b>Sửa Phương Thức Thanh Toán</b></div>
    <div class="card-body">
        <form action="{{ route('admin.thanhtoan.sua', $phuongthucthanhtoan->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Tên phương thức</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $phuongthucthanhtoan->tenphuongthucthanhtoan }}" required>
                @error('name')
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
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</div>
@endsection