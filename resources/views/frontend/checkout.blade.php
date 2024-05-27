@extends('frontend.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.homepage')}}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.shop') }}">Cửa hàng</a></li>
                    <li class="breadcrumb-item">Thanh toán</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form action="" id="orderForm" name="orderForm" method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Địa chị nhận hàng</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Tên" value="{{ (!empty($customerAddress)) ? $customerAddress->name : ''}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (!empty($customerAddress)) ? $customerAddress->email : ''}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Địa chỉ" class="form-control">{{ (!empty($customerAddress)) ? $customerAddress->address : ''}}</textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control" placeholder="Thành phố" value="{{ (!empty($customerAddress)) ? $customerAddress->city : ''}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Số điện thoại" value="{{ (!empty($customerAddress)) ? $customerAddress->mobile : ''}}">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Ghi chú(Kích cỡ, lưu ý, ...)" class="form-control" value="{{ (!empty($customerAddress)) ? $customerAddress->notes : ''}}"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Tổng cộng đơn hàng</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                <div class="d-flex justify-content-between pb-2">
                                    <div class="h6">{{$item->name}} X {{$item->qty}}</div>
                                    <div class="h6">{{number_format($item->qty*$item->price)}}đ</div>
                                </div>
                                @endforeach
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Tổng</strong></div>
                                    <div class="h6"><strong>{{number_format(Cart::subTotal(0,'',''))}}đ</strong></div>
                                </div>
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Giảm giá</strong></div>
                                    <div class="h6"><strong id="discount_value">{{$discount}}đ</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Phí vận chuyển</strong></div>
                                    <div class="h6"><strong>{{$shipping}}đ</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Tổng</strong></div>
                                    <div class="h5"><strong id="grandTotal">{{$grandTotal}}đ</strong></div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Coupon Code" class="form-control" name="discount-code" id="discount-code">
                            <button class="btn btn-dark" type="button" id="apply-discount">Nhập mã giảm giá</button>
                        </div>
                        <div id="discount-response-wrapper">
                            @if (Session::has('code'))
                                <div class="input-group mt-4" id="discount-response">
                                    <strong> {{ Session::get('code')->code }} </strong>
                                    <a class="btn btn-sm btn-danger" id="remove-discount"> <i class="fa fa-times"></i></a>
                                </div>
                            @endif
                        </div>
                        <div class="card payment-form ">
                            <h3 class="card-title h5 mb-3">Phương thức thanh toán</h3>
                            <div class="form-check">
                                <input checked type="radio" name="payment_method" value="cod" id="payment_method_one">
                                <label for="payment_method_one" class="form-check-label">COD</label>
                            </div>

                            <div class="form-check">
                                <input type="radio" name="payment_method" value="cod" id="payment_method_two">
                                <label for="payment_method_two" class="form-check-label">Thanh toán qua ngân hàng</label>
                            </div>

                            <div class="card-body p-0 d-none mt-3" id="card-payment-form">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">CVV Code</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4">
                                {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                                <button type="submit" class="btn-dark btn btn-block w-100">Đặt hàng</button>
                            </div>
                        </div>


                        <!-- CREDIT CARD FORM ENDS HERE -->

                    </div>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection

@section('customJS')
<script>
    $('#payment_method_one').click(function(){
        if ($(this).is(":checked") == true) {
            $('#card-payment-form').addClass('d-none');
        }
    });

    $('#payment_method_two').click(function(){
        if ($(this).is(":checked") == true) {
            $('#card-payment-form').removeClass('d-none');
        }
    });

    $('#orderForm').submit(function(event){
        event.preventDefault();
        $("button[type='submit']").prop('disabled', false);
        $.ajax({
            url: '{{route("frontend.processCheckout")}}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                var errors = response.errors;
                $("button[type='submit']").prop('disabled', false);

                if (response.status == false) {
                    if (errors.name) {
                        $("#name").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors.name);
                    } else {
                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors.email) {
                        $("#email").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors.email);
                    } else {
                        $("#email").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors.address) {
                        $("#address").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors.address);
                    } else {
                        $("#address").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors.city) {
                        $("#city").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors.city);
                    } else {
                        $("#city").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors.mobile) {
                        $("#mobile").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors.mobile);
                    } else {
                        $("#mobile").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                } else {
                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $("#email").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $("#address").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $("#city").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $("#mobile").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    window.location.href="{{ url('/thanks/') }}/"+response.orderId;
                }
            }
        });
    });



    $("#apply-discount").click(function(){
        $.ajax({
            url: '{{ route("frontend.applyDiscount")}}',
            type: 'post',
            data: {code: $('#discount-code').val()},
            dataType: 'json',
            success: function(response) {
                if (response.status == true) {
                    $("#grandTotal").html(response.grandTotal);
                    $("#discount_value").html(response.discount);
                    $("#discount-response-wrapper").html(response.discountHtml);
                } else {
                    $("#discount-response-wrapper").html("<span class='text-danger'>" + response.message + "</span>");
                }
            }
        });
    });

    $("#remove-discount").click(function(){
        $.ajax({
            url: '{{ route("frontend.removeDiscount")}}',
            type: 'post',
            data: {},
            dataType: 'json',
            success: function(response) {
                if (response.status == true) {
                    $("#grandTotal").html(response.grandTotal);
                    $("#discount_value").html(response.discount);
                    $('#discount-response').html('');
                }
            }
        });
    });

    $('body').on('click', "#remove-discount", function(){
        $.ajax({
            url: '{{ route("frontend.removeDiscount")}}',
            type: 'post',
            data: {},
            dataType: 'json',
            success: function(response) {
                if (response.status == true) {
                    $("#grandTotal").html(response.grandTotal);
                    $("#discount_value").html(response.discount);
                    $('#discount-response').html('');
                    $('#discount-code').val('');
                }
            }
        });
    });
</script>
@endsection
