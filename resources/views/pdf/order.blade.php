<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<style>
    * {
        font-family: DejaVu Sans, sans-serif;
    }

    table tr th {
        padding: 10px, 15px;
        font-size: 15px;
    }
    table tr td {
        text-align: center;
        padding: 10px, 15px;
    }
</style>
<div class="col-md-9">
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
                @if (!empty($order->notes))
                    <strong>Ghi chú: </strong>
                    {{ $order->notes }}
                @endif
                </div>
            </div>
        </div>
        <br>
        <div class="card-body table-responsive p-3">
            <table class="table table-striped" border="1" cellpadding="0" cellspacing="0">
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
</body>
</html>
