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
        font-size: 12px;
    }
    table tr td {
        text-align: center;
        padding: 10px, 15px;
    }
</style>
<div class="col-md-9" >
    <div class="card">
        <div class="card-header pt-3">
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                <h1 class="h5 mb-3">Thông tin hóa đơn</h1>
                <address>
                    <strong>Mã nhà cung cấp: </strong>{{ $record->supplier_code}}<br>
                    <strong>Tên nhà cung cấp: </strong>{{ $record->supplier_name}}<br>
                </address>
                <strong>Ngày nhập: </strong>
                    {{ \Carbon\Carbon::parse($record->created_at)->format('Y/m/d H:i:s')  }}
                </div>
            </div>
        </div>
        <br>
        <div class="card-body table-responsive p-3">
            <table class="table table-striped" border="1" cellpadding='0' cellspacing='0'>
                <thead>
                    <tr>
                        <th width="100">Mã sản phẩm</th>
                        <th width="100">Tên sản phẩm</th>
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

</body>
</html>
