@extends('admin.layouts.app')


@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chỉnh sửa danh mục con</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('sub-categories.index')}}" class="btn btn-primary">Quay lại</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="categoryForm" name="categoryForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name">Danh mục</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Chọn 1 danh mục</option>
                                    @if($categories->isNotEmpty())
                                    @foreach ($categories as $category)
                                        <option {{ ($subCategory->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id}}">{{ $category->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Tên danh mục</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Tên danh mục" value="{{ $subCategory->name}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $subCategory->slug}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Tình trạng</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ ($subCategory->category_id == 1) ? 'selected' : '' }} value="1">Hoạt động</option>
                                    <option {{ ($subCategory->category_id == 0) ? 'selected' : '' }} value="0">Không</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('sub-categories.index')}}" class="btn btn-outline-dark ml-3">Hủy</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@section('customJS')
<script type="text/javascript">
$("#categoryForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("sub-categories.update",$subCategory->id) }}',
        type: 'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response) {
        $("button[type=submit]").prop('disabled', false);

            if (response["status"] == true) {

                window.location.href="{{ route('sub-categories.index')}}";

                $("#name").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#slug").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");
            } else {

                if (response['notFound'] == true) {
                    window.location.href="{{ route('sub-categories.index')}}";
                }

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
