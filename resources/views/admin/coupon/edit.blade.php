@extends('admin.layouts.app')


@section('content')
<section class="content-header">
    @include('admin.message')
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chỉnh sửa mã giảm giá</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('coupons.index')}}" class="btn btn-primary">QUay lại</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="discountForm" name="discountForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code">Mã giảm giá</label>
                                <input type="text" value="{{ $coupon->code}}" name="code" id="code" class="form-control" placeholder="Mã giảm giá">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Tên mã giảm giá</label>
                                <input type="text" value="{{ $coupon->name}}" name="name" id="name" class="form-control" placeholder="Tên mã giảm giá">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses">Số lượt sử dụng tối đa của mã giảm giá</label>
                                <input type="number" value="{{ $coupon->max_uses}}" name="max_uses" id="max_uses" class="form-control" placeholder="Số lượt sử dụng tối đa của mã giảm giá">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses_user">Số lượt sử dụng tối đa của người dùng</label>
                                <input type="number" value="{{ $coupon->max_uses_user}}" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Số lượt sử dụng tối đa của người dùng">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Type">Đơn vị giảm</label>
                                <select name="type" id="type" class="form-control">
                                    <option {{ ($coupon->type == 'percent') ? 'selected' : ''}} value="percent">Phần trăm</option>
                                    <option {{ ($coupon->type == 'fixed') ? 'selected' : ''}} value="fixed">Cố định</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="discount_amount">Só tiền giảm giá</label>
                                <input type="number" value="{{ $coupon->discount_amount}}" name="discount_amount" id="discount_amount" class="form-control" placeholder="Só tiền giảm giá">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_amount">Số tiền tối thiểu đơn hàng cần để sử dụng mã giảm</label>
                                <input type="number" value="{{ $coupon->min_amount}}" name="min_amount" id="min_amount" class="form-control" placeholder="Số tiền tối thiểu đơn hàng cần để sử dụng mã giảm">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Tình trạng</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ ($coupon->status == 1) ? 'selected' : ''}} value="1">Hoạt động</option>
                                    <option {{ ($coupon->type == 0) ? 'selected' : ''}} value="0">Không</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="starts_at">Ngày bắt đầu</label>
                                <input type="text" value="{{ $coupon->starts_at}}" autocomplete="off" name="starts_at" id="starts_at" class="form-control" placeholder="Ngày bắt đầu">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expires_at">Ngày hết hạn</label>
                                <input type="text" value="{{ $coupon->expires_at}}" autocomplete="off" name="expires_at" id="expires_at" class="form-control" placeholder="Ngày hết hạn">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control" name="description" id="description" cols="30" rows="10">{{ $coupon->description}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('coupons.index')}}" class="btn btn-outline-dark ml-3">Hủy</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@section('customJS')
<script type="text/javascript">
    $(document).ready(function(){
        $('#starts_at').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });
    });

    $(document).ready(function(){
        $('#expires_at').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });
    });

$("#discountForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("coupons.update", $coupon->id) }}',
        type: 'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response) {
        $("button[type=submit]").prop('disabled', false);

            if (response["status"] == true) {

                window.location.href="{{ route('coupons.index')}}";

                $("#code").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#discount_amount").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#expires-at").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");
            } else {
                var errors = response['errors'];
                if (errors['code']) {
                    $("#code").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['code']);
                } else {
                    $("#code").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['discount_amount']) {
                    $("#discount_amount").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['discount_amount']);
                } else {
                    $("#discount_amount").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['expires_at']) {
                    $("#expires_at").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['expires_at']);
                } else {
                    $("#expires_at").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }
            }
        }, error: function(jqXHR, exception){
            console.log('Something went wrong!');
        }
    })
});

    $("#name").change(function(){
        console.log('a');
        element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: {title: element.val()},
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false);
                if (response["status"] == true) {
                    $("#slug").val(response["slug"]);
                }
            }
        });
    });

</script>
@endsection
