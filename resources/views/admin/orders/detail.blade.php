@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Đơn hàng: {{ $order->id}}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('orders.index')}}" class="btn btn-primary">Quay lại</a>
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
                            <h1 class="h5 mb-3">Thông tin đơn hàng</h1>
                            <address>
                                <strong>{{ $order->name}}</strong><br>
                                Địa chỉ: {{ $order->address}}<br>
                                Thành phố: {{ $order->city}}<br>
                                Điện thoại: {{ $order->mobile}}<br>
                                Email: {{ $order->email}}
                            </address>
                            <strong>Ngày giao hàng dự kiến</strong><br>
                            @if (!empty($order->shipped_date))
                                {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y')  }}
                            @else
                                n/a
                            @endif
                            </div>

                            <div class="col-sm-4 invoice-col">
                                {{-- <b>Invoice #007612</b><br>
                                <br> --}}
                                <b>Đơn hàng:</b> {{ $order->id}}<br>
                                <b>Tổng cộng:</b> {{ number_format($order->grand_total)}}đ<br>
                                <b>Tình trạng: </b>
                                @if ($order->status == 'pending')
                                    <span class="text-danger">Đang xử lý</span>
                                @elseif($order->status == 'shipped')
                                    <span class="text-info">Đã giao</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="text-warning">Bị hủy</span>
                                @else
                                    <span class="text-success">Đang vận chuyển</span>
                                @endif

                                @if (!empty($order->notes))
                                <br>
                                    <strong>Ghi chú: </strong>
                                    {{ $order->notes }}
                                @endif
                                <br>
                                @if (!empty($order->reason_cancel))
                                    <strong>Lý do hủy đơn: </strong>
                                    {{ $order->reason_cancel }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th width="100">Giá tiền</th>
                                    <th width="100">Số lượng</th>
                                    <th width="100">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $item)
                                    <tr>
                                        <td>{{ $item->name}}</td>
                                        <td>{{ number_format($item->price)}}đ</td>
                                        <td>{{ $item->qty}}</td>
                                        <td>{{ number_format($item->total)}}đ</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="3" class="text-left">Tổng:</th>
                                    <td>{{ number_format($order->subTotal) }}đ</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-left">Giảm giá:</th>
                                    <td>{{ number_format($order->discount) }}đ</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-left">Phí vận chuyển:</th>
                                    <td>{{ number_format($order->shipping) }}đ</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-left">Tổng cộng:</th>
                                    <td>{{ number_format($order->grand_total) }}đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Tình trạng đơn hàng</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ ($order->status == 'pending') ? 'selected' : ''}}>Đang xử lý</option>
                                    <option value="shipped" {{ ($order->status == 'shipped') ? 'selected' : ''}}>Đã giao</option>
                                    <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : ''}}>Đang vận chuyển</option>
                                    <option value="cancelled" {{ ($order->status == 'cancelled') ? 'selected' : ''}}>Bị hủy</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Ngày giao hàng dự kiến</label>
                                <input autocomplete="off" placeholder="Shipped date" value="{{ $order->shipped_date }}" type="text" name="shipped_date" id="shipped_date" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary">Cập nhật</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-3">In đơn hàng dưới dạng file pdf</h2>
                        <div class="mb-3">
                            <a href="{{ route('orders.downloadPdf', $order->id)}}" class="btn btn-primary">In đơn hàng</a>
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
    $(document).ready(function(){
        $('#shipped_date').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });
    });

    $("#changeOrderStatusForm").submit(function(event){
        event.preventDefault();
        $.ajax({
            url: '{{ route("orders.changeOrderStatus", $order->id) }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                window.location.href = '{{ route("orders.detail", $order->id) }}';
            }
        });
    });
</script>
@endsection
