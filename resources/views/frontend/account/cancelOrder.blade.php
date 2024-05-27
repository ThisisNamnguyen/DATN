@extends('frontend.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.account.orders') }}">Đơn hàng</a></li>
                    <li class="breadcrumb-item">Hủy đơn hàng</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('frontend.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Hủy đơn hàng</h2>
                        </div>
                        <form action="" name="cancelReason" id="cancelReason">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <textarea name="reason" id="reason" class="form-control" cols="30" rows="10" placeholder="Lý do hủy đơn của bạn là gì?"></textarea>
                                        <p></p>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-dark">Gửi</button>
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
    $('#cancelReason').submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '{{ route("frontend.account.cancelReason", $order->id)}}',
            type: 'put',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response["status"] == true) {

                window.location.href="{{ route('frontend.account.orders')}}";

                $("#reason").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                } else {
                    var errors = response['errors'];
                    if (errors['reason']) {
                        $("#reason").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['reason']);
                    } else {
                        $("#reason").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                }
            }
        });
    });
    </script>
@endsection
