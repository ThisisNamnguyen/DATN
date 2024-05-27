@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>
        <h4><i class="icon fa fa-ban"></i>Lỗi!</h4> {{Session::get('error')}}
    </div>
@endif


@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>
        <h4><i class="icon fa fa-ban"></i>Thành công!</h4> {{Session::get('success')}}
    </div>
@endif
