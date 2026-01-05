@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><b>Sửa đơn hàng</b></div>
    <div class="card-body">
        <form action="{{ route('admin.donhang.sua', ['id' => $donhang->id]) }}" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="nguoidung_id">Khách hàng</label>
                <input type="text" class="form-control" id="nguoidung" name="nguoidung_id" value="{{ $donhang->nguoidung ? $donhang->nguoidung->name : 'N/A' }}" disabled required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="dienthoaigiaohang">Điện thoại giao hàng</label>
                <input type="text" class="form-control @error('dienthoaigiaohang') is-invalid @enderror" id="dienthoaigiaohang" name="dienthoaigiaohang" value="{{ $donhang->dienthoaigiaohang }}" required />
                @error('dienthoaigiaohang')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="diachigiaohang">Địa chỉ giao hàng</label>
                <input type="text" class="form-control @error('diachigiaohang') is-invalid @enderror" id="diachigiaohang" name="diachigiaohang" value="{{ $donhang->diachigiaohang }}" required />
                @error('diachigiaohang')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="phuongthucthanhtoan">Phương thức thanh toán</label>
                <input type="text" class="form-control" id="phuongthucthanhtoan"
                    value="{{ $donhang->phuongthucthanhtoan->tenphuongthucthanhtoan }}" disabled>
                <input type="hidden" name="phuongthucthanhtoan_id"
                    value="{{ $donhang->phuongthucthanhtoan_id }}">
            </div>
            <div class="mb-3">
                <label class="form-label" for="tinhtrang_id">Tình trạng đơn hàng</label>
                <select class="form-select @error('tinhtrang_id') is-invalid @enderror" id="tinhtrang_id" name="tinhtrang_id" required>
                    <option value="">-- Chọn --</option>
                    @foreach($tinhtrang as $value)
                    <option value="{{ $value->id }}" {{ ($donhang->tinhtrang_id == $value->id) ? 'selected' : '' }}>{{ $value->tinhtrang }}</option>
                    @endforeach
                </select>
                @error('tinhtrang_id')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-light fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
@endsection