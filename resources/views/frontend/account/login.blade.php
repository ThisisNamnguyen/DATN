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
                    <li class="breadcrumb-item">Đăng nhập</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <div class="login-form">
                <form action="{{route('frontend.account.authenticate')}}" method="post">
                    @csrf
                    <h4 class="modal-title">Đăng nhập vào tài khoản của bạn</h4>
                    <div class="form-group">
                        <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required="required" name="email" value="{{old('email')}}">
                        @error('email')
                            <p class="invalid-feedback">{{ $message}}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mật khẩu" required="required" name="password">
                        @error('password')
                            <p class="invalid-feedback">{{ $message}}</p>
                        @enderror
                    </div>
                    <div class="form-group small">
                        <a href="{{ route('frontend.forgotPassword') }}" class="forgot-link">Quên mật khẩu?</a>
                    </div>
                    <input type="submit" class="btn btn-dark btn-block btn-lg" value="Đăng nhập">
                </form>
                <div class="text-center small">Chưa có tài khoản? <a href="{{route('frontend.account.register')}}">Đăng ký</a></div>
            </div>
        </div>
    </section>
</main>
@endsection
