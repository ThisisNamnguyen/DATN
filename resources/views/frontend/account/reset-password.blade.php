@extends('frontend.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success')}}
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error')}}
                </div>
            @endif
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('frontend.homepage')}}">Trang chủ</a></li>
                    <li class="breadcrumb-item">Đặt lại mật khẩu</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <div class="login-form">
                <form action="{{route('frontend.processResetPassword')}}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}" id="">
                    <h4 class="modal-title">Đặt lại mật khẩu</h4>
                    <div class="form-group">
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Mật khẩu mới" required="required" name="new_password" value="">
                        @error('new_pasword')
                            <p class="invalid-feedback">{{ $message}}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Xác nhận mật khẩu mới" required="required" name="confirm_password" value="">
                        @error('confirm_password')
                            <p class="invalid-feedback">{{ $message}}</p>
                        @enderror
                    </div>
                    <input type="submit" class="btn btn-dark btn-block btn-lg" value="Cập nhật mật khẩu">
                </form>
                <div class="text-center small">Quay lại <a href="{{route('frontend.account.login')}}">Đăng nhập</a></div>
            </div>
        </div>
    </section>
</main>
@endsection
