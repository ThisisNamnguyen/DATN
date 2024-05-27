@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    @include('admin.message')
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Nhập hàng</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.records.index')}}" class="btn btn-primary">Quay lại</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="recordForm" name="recordForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_code">Mã nhà cung cấp</label>
                                <input type="text" required name="supplier_code" id="supplier_code" class="form-control" placeholder="Mã nhà cung cấp">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_name">Tên nhà cung cấp</label>
                                <input type="text" required name="supplier_name" id="supplier_name" class="form-control" placeholder="Tên nhà cung cấp">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="number_product">Số sản phẩm</label>
                                <input type="number" required name="number_product" id="number_product" class="form-control" placeholder="Nhập số sản phẩm">
                                <input hidden type="number" name="number" id="number" class="form-control" placeholder="Nhập số sản phẩm">
                                <p></p>
                            </div>
                        </div>
                        <div id="addProduct" class="row" style="width: 100%">

                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Tạo</button>
                <a href="{{ route('admin.records.create')}}" class="btn btn-outline-dark ml-3">Hủy</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@section('customJS')
<script type="text/javascript">

$("#number_product").change(function(){
        element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("getProductNumber") }}',
            type: 'get',
            data: {number_product: element.val()},
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false);
                if (response["status"] == true) {
                    $("#number").val(response["number"]);
                    const list = document.getElementById("addProduct");

                    while (list.hasChildNodes()) {
                    list.removeChild(list.firstChild);
                    }
                    var num = response['number'];
                    console.log(num);
                    if (num > 0) {
                        for (var i = 1; i <= num; i++) {
                            $("#addProduct")
                            .append(`
                            <div class="col-md-12">
                                <h4>Mặt hàng ${i}</h4><br>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="barcode">Mã sản phẩm</label>
                                    <input type="text" name="barcode[]" id="barcode" required class="form-control barcode" placeholder="Mã sản phẩm">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product_name">Tên sản phẩm</label>
                                    <input type="text" name="product_name[]" id="product_name" class="form-control product_name" placeholder="Tên sản phẩm">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qty">Số lượng</label>
                                    <input type="number" name="qty[]" id="qty" class="form-control qty" placeholder="Số lượng">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="import_price">Giá nhập</label>
                                    <input type="number" name="import_price[]" id="import_price" class="form-control import_price" placeholder="Giá nhập">
                                    <p></p>
                                </div>
                            </div>`);
                        }
                    }
                }
            }
        });
    });

$("#recordForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    // $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("admin.records.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response) {
        // $("button[type=submit]").prop('disabled', false);

            if (response["status"] == true) {

                window.location.href="{{ route('admin.records.create')}}";

                // $("#supplier_code").removeClass('is-invalid')
                // .siblings('p')
                // .removeClass('invalid-feedback').html("");

                // $("#supplier_name").removeClass('is-invalid')
                // .siblings('p')
                // .removeClass('invalid-feedback').html("");

                // $("#number").removeClass('is-invalid')
                // .siblings('p')
                // .removeClass('invalid-feedback').html("");

                // $(".barcode").removeClass('is-invalid')
                // .siblings('p')
                // .removeClass('invalid-feedback').html("");

                // $(".product_name").removeClass('is-invalid')
                // .siblings('p')
                // .removeClass('invalid-feedback').html("");

                // $(".qty").removeClass('is-invalid')
                // .siblings('p')
                // .removeClass('invalid-feedback').html("");

                // $(".import_price").removeClass('is-invalid')
                // .siblings('p')
                // .removeClass('invalid-feedback').html("");
            } else {
                // var errors = response['errors'];
                // if (errors['supplier_code']) {
                //     $("#supplier_code").addClass('is-invalid')
                //     .siblings('p')
                //     .addClass('invalid-feedback').html(errors['supplier_code']);
                // } else {
                //     $("#supplier_code").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html("");
                // }

                // if (errors['supplier_name']) {
                //     $("#supplier_name").addClass('is-invalid')
                //     .siblings('p')
                //     .addClass('invalid-feedback').html(errors['supplier_name']);
                // } else {
                //     $("#supplier_name").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html("");
                // }

                // if (errors['number']) {
                //     $("#number").addClass('is-invalid')
                //     .siblings('p')
                //     .addClass('invalid-feedback').html(errors['number']);
                // } else {
                //     $("#number").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html("");
                // }

                // if (errors['barcode']) {
                //     $(".barcode").addClass('is-invalid')
                //     .siblings('p')
                //     .addClass('invalid-feedback').html(errors['barcode']);
                // } else {
                //     $(".barcode").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html("");
                // }

                // if (errors['product_name']) {
                //     $(".product_name").addClass('is-invalid')
                //     .siblings('p')
                //     .addClass('invalid-feedback').html(errors['product_name']);
                // } else {
                //     $(".product_name").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html("");
                // }

                // if (errors['qty']) {
                //     $(".qty").addClass('is-invalid')
                //     .siblings('p')
                //     .addClass('invalid-feedback').html(errors['qty']);
                // } else {
                //     $(".qty").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html("");
                // }

                // if (errors['import_price']) {
                //     $(".import_price").addClass('is-invalid')
                //     .siblings('p')
                //     .addClass('invalid-feedback').html(errors['import_price']);
                // } else {
                //     $(".import_price").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html("");
                // }
            }
        }, error: function(jqXHR, exception){
            console.log('Something went wrong!');
        }
    })
});

</script>
@endsection
