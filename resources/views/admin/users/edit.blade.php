@extends('admin.layouts.app')


@section('content')
<section class="content-header">
    @include('admin.message')
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chỉnh sửa người dùng</h1>
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
                                <input type="text" name="name" id="name" class="form-control" placeholder="Tên người" value="{{ $user->name}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="email" value="{{ $user->email}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone">Điện thoại</label>
                                <input type="number" name="phone" id="phone" class="form-control" placeholder="Điện thoại" value="{{ $user->phone}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password">Mật khẩu</label>
                                <input type="text" name="password" id="password" class="form-control" placeholder="Mật khẩu">
                                <span>Nếu thay đổi mật khẩu bạn phải nhập mật khẩu mới vào ô, nếu không cần thay đổi thì để trống</span>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Tình trạng</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ ($user->status == 1) ? 'selected' : ''}} value="1">Hoạt động</option>
                                    <option {{ ($user->status == 0) ? 'selected' : ''}} value="0">Không</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('users.index')}}" class="btn btn-outline-dark ml-3">Hủy</a>
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
        url: '{{ route("users.update",$user->id) }}',
        type: 'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response) {
        $("button[type=submit]").prop('disabled', false);

        if (response["status"] == true) {

            window.location.href="{{ route('users.index')}}";

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

    Dropzone.autoDiscover = false;
    const dropzone = new Dropzone("#image", {
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
        },
        url: '{{ route("temp-images.create") }}',
        maxFiles: 1,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, success: function(file, response) {
            $("#image_id").val(response.image_id);
        }
    });

</script>
@endsection
