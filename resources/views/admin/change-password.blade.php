@extends('admin.layouts.app')


@section('content')
<section class="content-header">
    @include('admin.message')
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Đổi mật khẩu</h1>
            </div>
            <div class="col-sm-6 text-right">
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="changePasswordForm" name="changePasswordForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="old_password">Mật khẩu cũ</label>
                                <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Mật khẩu cũ">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="new_password">Mật khẩu mới</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Mặt khẩu mới">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="confirm_password">Xác nhận lại mật khẩu</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Xác nhận lại mật khẩu">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@section('customJS')
<script type="text/javascript">
$("#changePasswordForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("admin.processChangePassword") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response) {
        $("button[type=submit]").prop('disabled', false);

            if (response["status"] == true) {

                window.location.href="{{ route('admin.showChangePasswordForm')}}";

                $("#old_password").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#new_password").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#confirm_password").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");
            } else {
                var errors = response['errors'];
                if (errors['old_password']) {
                    $("#old_password").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['old_password']);
                } else {
                    $("#old_password").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['new_password']) {
                    $("#new_password").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['new_password']);
                } else {
                    $("#new_password").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['confirm_password']) {
                    $("#confirm_password").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['confirm_password']);
                } else {
                    $("#confirm_password").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }
            }
        }, error: function(jqXHR, exception){
            console.log('Something went wrong!');
        }
    })
});


</script>
@endsection
