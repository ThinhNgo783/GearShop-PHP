@extends('layouts.frontend')

@section('title', 'Đăng nhập')

@section('content')
<div class="container py-4 my-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <h2 class="h4 mb-1">Đăng nhập</h2>
                    <div class="py-3">
                        <h3 class="d-inline-block align-middle fs-base fw-medium mb-2 me-2">Đăng nhập với:</h3>
                        <div class="d-inline-block align-middle">
                            <a class="btn-social bs-google me-2 mb-2" href="{{ route('google.login') }}" data-bs-toggle="tooltip" title="Đăng nhập với Google">
                                <i class="ci-google"></i>
                            </a>
                            <a class="btn-social bs-facebook me-2 mb-2" href="#" data-bs-toggle="tooltip" title="Đăng nhập với Facebook">
                                <i class="ci-facebook"></i>
                            </a>
                            <a class="btn-social bs-twitter me-2 mb-2" href="#" data-bs-toggle="tooltip" title="Đăng nhập với Twitter">
                                <i class="ci-twitter"></i>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <h3 class="fs-base pt-4 pb-2">Hoặc sử dụng thông tin đã có</h3>
                    <form method="post" action="{{ route('login') }}" class="needs-validation" novalidate>
                        @csrf
                        @if($errors->has('email') || $errors->has('username'))
                        <div class="alert alert-danger fs-base" role="alert">
                            <i class="ci-close-circle me-2"></i>{{ empty($errors->first('email')) ? $errors->first('username') : $errors->first('email') }}
                        </div>
                        @endif
                        <div class="input-group mb-3">
                            <i class="ci-user position-absolute top-50 translate-middle-y text-muted fs-base ms-3"></i>
                            <input type="text" class="form-control rounded-start {{ ($errors->has('email') || $errors->has('username')) ? 'is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" placeholder="Email, Tên đăng nhập hoặc Điện thoại" required />
                        </div>
                        <div class="input-group mb-3">
                            <i class="ci-locked position-absolute top-50 translate-middle-y text-muted fs-base ms-3"></i>
                            <div class="password-toggle w-100">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Mật khẩu" required />
                                <label class="password-toggle-btn" aria-label="Hiện/Ẩn mật khẩu">
                                    <input class="password-toggle-check" type="checkbox" />
                                    <span class="password-toggle-indicator"></span>
                                </label>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                                <label class="form-check-label" for="remember">Duy trì đăng nhập</label>
                            </div>
                            @if (Route::has('register'))
                            <a class="nav-link-inline fs-sm" href="{{ route('user.dangky') }}">Chưa có tài khoản?</a>
                            @endif
                            @if (Route::has('password.request'))
                            <a class="nav-link-inline fs-sm" href="{{ route('password.request') }}">
                                {{ __('Quên mật khẩu?') }}
                            </a>
                            @endif
                        </div>
                        <hr class="mt-4">
                        <div class="text-end pt-4">
                            <button class="btn btn-primary" type="submit"><i class="ci-sign-in me-2 ms-n21"></i>Đăng nhập</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
@endsection