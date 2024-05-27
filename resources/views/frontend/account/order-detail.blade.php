@extends('frontend.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.account.orders') }}">Đơn hàng</a></li>
                    <li class="breadcrumb-item">Chi tiết đơn hàng</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('frontend.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Đơn hàng: {{ $order->id}}</h2>
                        </div>

                        <div class="card-body pb-0">
                            <!-- Info -->
                            <div class="card card-sm">
                                <div class="card-body bg-light mb-3">
                                    <div class="row">
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Đơn hàng:</h6>
                                            <!-- Text -->
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                                {{ $order->id}}
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Ngày giao hàng dự kiến</h6>
                                            <!-- Text -->
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                                <time datetime="">
                                                    @if (!empty($order->shipped_date))
                                                        {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y')  }}
                                                    @else
                                                        n/a
                                                    @endif
                                                </time>
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Tình trạng:</h6>
                                            <!-- Text -->
                                            <p class="mb-0 fs-sm fw-bold">
                                                @if ($order->status == 'pending')
                                                    <span class="badge bg-danger">Đang xử lý</span>
                                                @elseif($order->status == 'shipped')
                                                    <span class="badge bg-info">Đã giao</span>
                                                @elseif($order->status == 'cancelled')
                                                    <span class="badge bg-warning">Bị hủy</span>
                                                @else
                                                    <span class="badge bg-success">Đang vận chuyển</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Tổng tiền đơn hàng:</h6>
                                            <!-- Text -->
                                            <p class="mb-0 fs-sm fw-bold">
                                            {{ number_format($order->grand_total)}}đ
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer p-3">

                            <!-- Heading -->
                            <h6 class="mb-7 h5 mt-4">Tổng số sản phẩm: {{ $orderItemCount }}</h6>

                            <!-- Divider -->
                            <hr class="my-3">

                            <!-- List group -->
                            <ul>
                                @foreach ($orderItems as $item)
                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-4 col-md-3 col-xl-2">
                                                <!-- Image -->
                                                {{-- <a href="product.html"><img src="images/product-1.jpg" alt="..." class="img-fluid"></a> --}}
                                                @php
                                                    $productImage = getProductImage($item->product_id);
                                                @endphp
                                                @if (!empty($productImage->image))
                                                <img src="{{ asset('/uploads/product/small/'.$productImage->image)}}" class="img-fluid" ></td>
                                                @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png')}}" class="img-fluid " ></td>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <!-- Title -->
                                                <p class="mb-4 fs-sm fw-bold">
                                                    <a class="text-body" href="#">{{ $item->name}} x {{ $item->qty}}</a> <br>
                                                    <span class="text-muted">{{ number_format($item->total)}}đ</span>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="card card-lg mb-5 mt-3">
                        <div class="card-body">
                            <!-- Heading -->
                            <h6 class="mt-0 mb-3 h5">Tổng đơn hàng</h6>

                            <!-- List group -->
                            <ul>
                                <li class="list-group-item d-flex">
                                    <span>Tổng</span>
                                    <span class="ms-auto">{{ number_format($order->subTotal)}}đ</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Giảm giá {{ (!empty($order->coupon_code)) ? '('.$order->coupon_code.')' : ''}}</span>
                                    <span class="ms-auto">{{ number_format($order->discount)}}đ</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Phí vận chuyển</span>
                                    <span class="ms-auto">{{ number_format($order->shipping)}}đ</span>
                                </li>
                                <li class="list-group-item d-flex fs-lg fw-bold">
                                    <span>Tổng tiền</span>
                                    <span class="ms-auto">{{ number_format($order->grand_total)}}đ</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card card-lg mb-5 mt-3">
                        @if ($order->status == 'pending')
                            <div class="card-body">
                                <a class='link-dark' href="{{ route('frontend.account.cancelOrder', $order->id)}}">Hủy đơn</a>
                            </div>
                        @endif
                        <div class="card-body">
                            <a class='link-dark' href="">Cần hỗ trợ?</a>
                        </div>
                        @if ($order->status == 'delivered')
                        <form action="" method="post" id="changeOrderStatus" name="changeOrderStatus">
                            <div class="card-body">
                                <button type="submit" class='btn btn-white'>
                                    <a class='link-dark' href="{{ route('frontend.account.changeOrderStatus', $order->id)}}">Đã nhận được hàng</a>
                                </button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('customJS')
    <script>
        $("#changeOrderStatus").submit(function(event){
        event.preventDefault();
        $.ajax({
            url: '{{ route("frontend.account.changeOrderStatus", $order->id) }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                window.location.href = '{{ route("frontend.account.orderDetail", $order->id) }}';
            }
        });
    });
    </script>
@endsection
