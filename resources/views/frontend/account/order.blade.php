@extends('frontend.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('frontend.account.profile')}}">Tài khoản</a></li>
                    <li class="breadcrumb-item">Đơn hàng</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-12">
                    @include('frontend.account.common.message')
                </div>
                <div class="col-md-3">
                    @include('frontend.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Đơn hàng</h2>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Đơn hàng</th>
                                            <th>Ngày thanh toán</th>
                                            <th>Tình trạng</th>
                                            <th>Tổng cộng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($orders->isNotEmpty())
                                            @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('frontend.account.orderDetail', $order->id)}}">{{ $order->id}}</a>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y')}}</td>
                                                <td>
                                                    @if ($order->status == 'pending')
                                                    <span class="badge bg-danger">Đang xử lý</span>
                                                    @elseif($order->status == 'shipped')
                                                    <span class="badge bg-info">Đã giao</span>
                                                    @elseif($order->status == 'cancelled')
                                                    <span class="badge bg-warning">Bị hủy</span>
                                                    @else
                                                    <span class="badge bg-success">Đang vận chuyển</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($order->grand_total)}}đ</td>
                                            </tr>
                                            @endforeach

                                            @else
                                            <tr>
                                                <td colspan="3">Không có đơn hàng nào</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
