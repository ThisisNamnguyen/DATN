@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    @include('admin.message')
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chỉnh sửa sản phẩm</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index')}}" class="btn btn-primary">Quay lại</a>
                </div>
            </div>
        </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="" method="post" name="productForm" id="productForm">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="product_code">Tên sản phẩm</label>
                                        <select name="product_code" id="product_code" class="form-control">
                                            <option value="">Chọn 1 sản phẩm</option>
                                            @if($records->isNotEmpty())
                                                @foreach ($records as $item)
                                                    {{-- <option value="{{ $item->id}}">{{ $item->product_name}}</option> --}}
                                                    <option {{($product->barcode == $item->barcode) ? 'selected' : ''}} value="{{ $item->barcode}}">{{ $item->product_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="barcode">Mã sản phẩm</label>
                                        <input readonly type="text" name="barcode" id="barcode" class="form-control" placeholder="Mã sản phẩm" value="{{$product->barcode}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Tiêu đề</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Tiêu đề" value="{{$product->title}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{$product->slug}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="short_description">Mô tả ngắn</label>
                                        <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Mô tả ngắn">{{$product->short_description}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Mô tả chi tiết</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Mô tả chi tiết">{{$product->description}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="shipping_returns">Thông số kỹ thuật</label>
                                        <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="Thông số kỹ thuật">{{$product->shipping_returns}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Ảnh</h2>
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    <br>Thả ảnh vào đây hoặc bấm để tải ảnh lên<br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="product-gallery">
                        @if ($productImages->isNotEmpty())
                            @foreach ($productImages as $image)
                                <div class="col-md-3" id="image-row-{{$image->id}}">
                                    <div class="card">
                                        <input type="hidden" name="image_array[]" value="{{$image->id}}">
                                        <img class="card-img-top" src="{{ asset('uploads/product/small/'.$image->image)}}" alt="Card image cap">
                                        <div class="card-body">
                                            <a href="javascript:void(0)" onclick="deleteImage({{$image->id}})" class="btn btn-primary">Xóa</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Giá</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Giá bán</label>
                                        <input type="text" name="price" id="price" class="form-control" placeholder="Giá bán" value="{{$product->price}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Giá gốc</label>
                                        <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Giá gốc" value="{{$product->compare_price}}">
                                        <p class="text-muted mt-3">
                                            Để hiển thị giảm giá, nhập giá gốc của sản phẩm vào "Giá gốc". Nhập vào "Giá bán" giá trị nhỏ hơn giá gốc
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Hàng hóa</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit">Đơn vị tính</label>
                                        <input type="text" name="unit" id="unit" class="form-control" placeholder="Đơn vị tính" value="{{$product->unit}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" name="track_qty" value="No">
                                            <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" {{($product->track_qty == 'Yes') ? 'checked' : ''}}>
                                            <label for="track_qty" class="custom-control-label">Theo dõi số lượng</label>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" readonly min="0" name="qty" id="qty" class="form-control" placeholder="Qty" value="{{$product->qty}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Sản phẩm liên quan</h2>
                            <div class="mb-3">
                                <select multiple class="related-product w-100" name="related_products[]" id="related_products">
                                    @if (!empty($relatedProducts))
                                        @foreach ($relatedProducts as $relProduct)
                                            <option selected value="{{ $relProduct->id}}">{{ $relProduct->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Tình trạng</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option {{($product->status == 1) ? 'selected' : ''}} value="1">Hoạt động</option>
                                    <option {{($product->status == 1) ? 'selected' : ''}} value="0">Không</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4  mb-3">Danh mục sản phẩm</h2>
                            <div class="mb-3">
                                <label for="category">Danh mục</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Chọn 1 danh mục</option>
                                    @if($categories->isNotEmpty())
                                        @foreach ($categories as $category)
                                            <option {{($product->category_id == $category->id) ? 'selected' : ''}} value="{{ $category->id}}">{{ $category->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="category">Danh mục con</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    <option value="">Chọn danh mục con</option>
                                    @if($subCategories->isNotEmpty())
                                        @foreach ($subCategories as $subCategory)
                                            <option {{($product->sub_category_id == $subCategory->id) ? 'selected' : ''}} value="{{ $subCategory->id}}">{{ $subCategory->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Sản phẩm nổi bật</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option {{($product->is_featured == "No") ? 'selected' : ''}} value="No">Không</option>
                                    <option {{($product->is_featured == "Yes") ? 'selected' : ''}} value="Yes">Có</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('products.index')}}" class="btn btn-outline-dark ml-3">Hủy</a>
            </div>
        </div>
    </form>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJS')
<script type="text/javascript">
    $('.related-product').select2({
        ajax: {
            url: '{{ route("products.getProducts")}}',
            dataType: 'json',
            tags: true,
            multiple: true,
            minimumInputLength: 3,
            processResults: function(data) {
                return {
                    results: data.tags
                };
            }
        }
    });

    $("#title").change(function(){
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

    $("#productForm").submit(function(event){
    event.preventDefault();
    var formArray = $(this).serializeArray()
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("products.update",$product->id)}}',
        type: 'put',
        data: formArray,
        dataType: 'json',
        success: function(response) {
        $("button[type=submit]").prop('disabled', false);

            if (response["status"] == true) {

                window.location.href="{{ route('products.index')}}";

                $(".error").removeClass('invalid-feedback').html('');
                $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

            } else {
                var errors = response['errors'];

                $(".error").removeClass('invalid-feedback').html('');
                $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                $.each(errors, function(key, value) {
                    $(`#${key}`).addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(value);
                });
            }
        }, error: function(jqXHR, exception){
            console.log('Something went wrong!');
        }
    })
});

    $("#category").change(function(){
        var category_id = $(this).val();
        $.ajax({
            url: '{{ route("product-subcategories.index")}}',
            type: 'get',
            data: {category_id:category_id},
            dataType: 'json',
            success: function(response) {
                $("#sub_category").find("option").not(":first").remove();
                $.each(response["subCategories"], function(key, item){
                    $("#sub_category").append(`<option value='${item.id}'>${item.name}</option>`)
                });
            },
            error: function() {
                console.log("Something went wrong");
            }
        });
    });

    $("#product_code").change(function(){
        var product_code_id = $(this).val();
        $.ajax({
            url: '{{ route("product-quantity.index")}}',
            type: 'get',
            data: {product_code_id:product_code_id},
            dataType: 'json',
            success: function(response) {
                if (response["status"] == true) {
                    $("#qty").val(response["qty"]);
                    $("#barcode").val(response["barcode"]);
                }
            },
            error: function() {
                console.log("Something went wrong");
            }
        });
    });

Dropzone.autoDiscover = false;
    const dropzone = new Dropzone("#image", {

        url: '{{ route("product-images.update") }}',
        maxFiles: 10,
        paramName: 'image',
        params: {'product_id': '{{$product->id}}'},
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, success: function(file, response) {
        //$("#image_id").val(response.image_id);
            var html = `<div class="col-md-3" id="image-row-${response.image_id}"><div class="card">
                <input type="hidden" name="image_array[]" value="${response.image_id}">
                <img class="card-img-top" src="${response.ImagePath}" alt="Card image cap">
                <div class="card-body">
                    <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-primary">Delete</a>
                </div>
            </div></div>`;

            $("#product-gallery").append(html);
        },
        complete: function(file) {
            this.removeFile(file);
        }
    });

    $("#product_code").change(function(){
        var product_code_id = $(this).val();
        $.ajax({
            url: '{{ route("product-quantity.index")}}',
            type: 'get',
            data: {product_code_id:product_code_id},
            dataType: 'json',
            success: function(response) {
                if (response["status"] == true) {
                    $("#qty").val(response["qty"]);
                    $("#barcode").val(response["barcode"]);
                }
            },
            error: function() {
                console.log("Something went wrong");
            }
        });
    });

    function deleteImage(id) {
        $("#image-row-"+id).remove();
        if (confirm("Are you sure you want to delete this image?")) {
            $.ajax({
                url: '{{route("product-images.delete")}}',
                type: 'delete',
                data: {id:id},
                success: function(response) {
                    if (response.status == true) {
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }
</script>
@endsection
