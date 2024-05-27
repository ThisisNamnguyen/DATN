@extends('frontend.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Tài khoản</a></li>
                    <li class="breadcrumb-item">Cài đặt</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-12">
                    @include('frontend.account.common.message')
                </div>
                <div class="col-md-3">
                    @include('frontend.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Thông tin cá nhân</h2>
                        </div>
                        <form action="" name="profileForm" id="profileForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Tên</label>
                                        <input value="{{ $user->name }}" type="text" name="name" id="name" placeholder="Nhập tên bạn" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ $user->email }}" type="text" name="email" id="email" placeholder="Nhập email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Điện thoại</label>
                                        <input value="{{ $user->phone }}" type="text" name="phone" id="phone" placeholder="Nhập số điện thoại" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-dark">Cập nhật</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Địa chỉ nhận hàng</h2>
                        </div>
                        <form action="" name="addressForm" id="addressForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Tên người nhận</label>
                                        <input value="{{ ($address == null) ? '' : $address->name}}" type="text" name="name" id="name" placeholder="Nhập tên" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ ($address == null) ? '' : $address->email}}" type="text" name="email" id="email" placeholder="Nhập email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile">Điện thoại</label>
                                        <input value="{{ ($address == null) ? '' : $address->mobile}}" type="text" name="mobile" id="mobile" placeholder="Nhập số điện thoại" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3">
                                        <label for="city">Thành phố</label>
                                        <input value="{{ ($address == null) ? '' : $address->city}}" type="text" name="city" id="city" placeholder="Nhập tên thành phố" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address">Địa chỉ</label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="5" placeholder="Nhập địa chỉ">{{ ($address == null) ? '' : $address->address}}</textarea>
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-dark">Cập nhật</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('customJS')
    <script>
    $('#profileForm').submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '{{ route("frontend.account.updateProfile")}}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response["status"] == true) {

                window.location.href="{{ route('frontend.account.profile')}}";

                $("#name").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#email").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#phone").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");
                } else {
                    var errors = response['errors'];
                    if (errors['name']) {
                        $("#name").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['email']) {
                        $("#email").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['email']);
                    } else {
                        $("#email").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['phone']) {
                        $("#phone").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['phone']);
                    } else {
                        $("#phone").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }
                }
            }
        });
    });

    $('#addressForm').submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '{{ route("frontend.account.updateAddress")}}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response["status"] == true) {

                window.location.href="{{ route('frontend.account.profile')}}";

                $("#addressForm #name").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#addressForm #email").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#mobile").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#city").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#address").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");
                } else {
                    var errors = response['errors'];
                    if (errors['name']) {
                        $("#addressForm #name").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $("#addressForm #name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['email']) {
                        $("#addressForm #email").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['email']);
                    } else {
                        $("#addressForm #email").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['mobile']) {
                        $("#mobile").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['mobile']);
                    } else {
                        $("#mobile").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['city']) {
                        $("#city").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['city']);
                    } else {
                        $("#city").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['address']) {
                        $("#address").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['address']);
                    } else {
                        $("#address").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }
                }
            }
        });
    });
    </script>
@endsection
