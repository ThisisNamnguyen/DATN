@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Hóa đơn: {{ $record->id}}</h1>
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
        <div class="row">
            <div class="col-md-9">
                @include('admin.message')
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                            <h1 class="h5 mb-3">Thông tin</h1>
                            <address>
                                <strong>Mã nhà cung cấp: </strong>{{ $record->supplier_code}}<br>
                                <strong>Tên nhà cung cấp: </strong>{{ $record->supplier_name}}<br>
                            </address>
                            <strong>Ngày nhập: </strong>
                                {{ \Carbon\Carbon::parse($record->created_at)->format('Y/m/d H:i:s')  }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mã sản phẩm</th>
                                    <th>Tên sản phẩm</th>
                                    <th width="100">Giá nhập</th>
                                    <th width="100">Số lượng</th>
                                    <th width="100">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($importItem))
                                    @foreach ($importItem as $item)
                                    <tr>
                                        <td>{{ $item->barcode}}</td>
                                        <td>{{ $item->product_name}}</td>
                                        <td>{{ number_format($item->import_price)}}đ</td>
                                        <td>{{ $item->qty}}</td>
                                        <td>{{ number_format($item->total_price)}}đ</td>
                                    </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <th colspan="4" class="text-left">Tổng cộng: </th>
                                    <td>{{ number_format($total) }}đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-3">In hóa đơn dưới dạng file pdf</h2>
                        <div class="mb-3">
                            <a href="{{ route('admin.records.downloadPdf', $record->id)}}" class="btn btn-primary">In hóa đơn</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection


@section('customJS')
<script>
</script>
@endsection
