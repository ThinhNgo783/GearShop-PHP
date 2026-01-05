@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><b>Thêm Sản phẩm</b></div>
    <div class="card-body">
        <form action="{{ route('admin.sanpham.them') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="loaisanpham_id">Loại sản phẩm</label>
                <select class="form-select @error('loaisanpham_id') is-invalid @enderror" id="loaisanpham_id" name="loaisanpham_id" required>
                    <option value="">-- Chọn --</option>
                    @foreach($loaisanpham as $value)
                    <option value="{{ $value->id }}">{{ $value->tenloai }}</option>
                    @endforeach
                </select>
                @error('loaisanpham_id')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="hangsanxuat_id">Hãng sản xuất</label>
                <select class="form-select @error('hangsanxuat_id') is-invalid @enderror" id="hangsanxuat_id" name="hangsanxuat_id" required>
                    <option value="">-- Chọn --</option>
                    @foreach($hangsanxuat as $value)
                    <option value="{{ $value->id }}">{{ $value->tenhang }}</option>
                    @endforeach
                </select>
                @error('hangsanxuat_id')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="tensanpham">Tên sản phẩm</label>
                <input type="text" class="form-control @error('tensanpham') is-invalid @enderror" id="tensanpham" name="tensanpham" value="{{ old('tensanpham') }}" required />
                @error('tensanpham')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="soluong">Số lượng</label>
                <input type="number" min="0" class="form-control @error('soluong') is-invalid @enderror" id="soluong" name="soluong" value="{{ old('soluong') }}" required />
                @error('soluong')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="gianhap">Giá nhập sản phẩm</label>
                <input type="number" min="0" class="form-control @error('gianhap') is-invalid @enderror" id="gianhap" name="gianhap" value="{{ old('gianhap') }}" required />
                @error('gianhap')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="dongia">Đơn giá</label>
                <input type="number" min="0" class="form-control @error('dongia') is-invalid @enderror" id="dongia" name="dongia" value="{{ old('dongia') }}" required />
                @error('dongia')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="hinhanh">Hình ảnh sản phẩm</label>
                <input type="file" class="form-control @error('hinhanh') is-invalid @enderror" id="hinhanh" name="hinhanh" value="{{ old('hinhanh') }}" />
                @error('hinhanh')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="motasanpham">Mô tả sản phẩm</label>
                <textarea class="form-control" id="motasanpham" name="motasanpham">{{ old('motasanpham') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-light fa-save"></i> Thêm vào CSDL</button>
        </form>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('public/vendor/ckeditor5/ckeditor.js') }}"></script>
<script>
    ClassicEditor.create(document.querySelector('#motasanpham'), {
        licenseKey: '',
    }).then(editor => {
        window.editor = editor;
    }).catch(error => {
        console.error(error);
    });
</script>
@endsection