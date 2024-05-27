@extends('admin.layouts.app')


@section('content')
<section class="content-header">
    @include('admin.message')
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tạo trang mới</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('pages.index')}}" class="btn btn-primary">Quay lại</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="pageForm" name="pageForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Tên trang</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Tên trang">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="col_num">Cột số</label>
                                <input type="number" name="col_num" id="col_num" class="form-control" placeholder="Cột số">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="content">Nội dung</label>
                                <textarea class="summernote" class="form-control" name="content" id="content" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Tạo</button>
                <a href="{{ route('pages.create')}}" class="btn btn-outline-dark ml-3">Hủy</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@section('customJS')
<script type="text/javascript">
$("#pageForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("pages.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response) {
        $("button[type=submit]").prop('disabled', false);

            if (response["status"] == true) {

                window.location.href="{{ route('pages.create')}}";

                $("#name").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#slug").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#col_num").removeClass('is-invalid')
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

                if (errors['slug']) {
                    $("#slug").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['slug']);
                } else {
                    $("#slug").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['col_num']) {
                    $("#col_num").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['col_num']);
                } else {
                    $("#col_num").removeClass('is-invalid')
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
