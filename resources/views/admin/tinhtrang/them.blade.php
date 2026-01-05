@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><b>Thêm tình trạng đơn hàng</b></div>
    <div class="card-body">
        <form action="{{ route('admin.tinhtrang.them') }}" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="tenloai">Tên tình trạng</label>
                <input type="text" class="form-control" id="tinhtrang" name="tinhtrang" required />
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-light fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>

@endsection
