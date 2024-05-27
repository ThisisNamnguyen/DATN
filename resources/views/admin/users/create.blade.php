@extends('admin.layouts.app')


@section('content')
<section class="content-header">
    @include('admin.message')
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tạo người dùng mới</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('users.index')}}" class="btn btn-primary">Quay lại</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="userForm" name="userForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Tên người dùng</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Tên người dùng">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="email">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone">Điện thoại</label>
                                <input type="number" name="phone" id="phone" class="form-control" placeholder="Điện thoại">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password">Mật khẩu</label>
                                <input type="text" name="password" id="password" class="form-control" placeholder="Mật khẩu">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Tình trạng</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Không</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Tạo</button>
                <a href="{{ route('users.create')}}" class="btn btn-outline-dark ml-3">Hủy</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@section('customJS')
<script type="text/javascript">
$("#userForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("users.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response) {
        $("button[type=submit]").prop('disabled', false);

            if (response["status"] == true) {

                window.location.href="{{ route('users.create')}}";

                $("#name").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#email").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#phone").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#password").removeClass('is-invalid')
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

                if (errors['password']) {
                    $("#password").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['password']);
                } else {
                    $("#password").removeClass('is-invalid')
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
