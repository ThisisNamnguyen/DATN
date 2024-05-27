@extends('admin.layouts.app')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Trang chủ</h1>
            </div>
            <div class="col-sm-6">

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
            <div class="col-md-3">
                <div class="small-box card">
                    <div class="inner bg-info">
                        <p>Tổng số đơn hàng</p>
                        <h3>{{ $totalOrders }}</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ route('orders.index') }}" class="small-box-footer text-dark">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box card bg-warning">
                    <div class="inner">
                        <p>Tổng người dùng</p>
                        <h3>{{ $totalUsers }}</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('users.index') }}" class="small-box-footer text-dark">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box card bg-success">
                    <div class="inner">
                        <p>Tổng số sản phẩm</p>
                        <h3>{{ $totalProducts }}</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('products.index') }}" class="small-box-footer text-dark">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box card bg-danger">
                    <div class="inner">
                        <p>Tổng số đánh giá</p>
                        <h3>{{ $totalRating }}</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('products.productRatings') }}" class="small-box-footer text-dark">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-7 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Thống kê số lượng mặt hàng trên từng danh mục hàng
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="chart tab-pane active" id="sales-chart" style="position: relative; height: 300px;">
                                <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

                <div class="card bg-gradient-success col-lg-5">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="far fa-calendar-alt"></i>
                            Lịch
                        </h3>
                    </div>

                    <div class="card-body pt-0">

                        <div id="calendar" style="width: 100%"></div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="small-box card bg-dark bg-gradient">
                    <div class="inner">
                        <p>Tổng tiền hàng đã nhập</p>
                        <h3>{{ number_format($totalImportMoney) }}đ</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('admin.records.index') }}" class="small-box-footer text-dark">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box card bg-secondary bg-gradient">
                    <div class="inner">
                        <p>Tổng doanh thu</p>
                        <h3>{{ number_format($totalRevenue)}}đ</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div>

        <div class="col-md-3">
            <div class="small-box card bg-light bg-gradient">
                <div class="inner">
                    <p>Doanh thu tháng này</p>
                    <h3>{{ number_format($revenueThisMonth)}}đ</h3>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box card bg-info bg-gradient">
                <div class="inner">
                    <p>Tổng lợi nhuận</p>
                    <h3>{{ number_format($profit)}}đ</h3>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@section('customJS')
<script>
$(function () {

'use strict'
  $('.daterange').daterangepicker({
    ranges   : {
      'Today'       : [moment(), moment()],
      'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month'  : [moment().startOf('month'), moment().endOf('month')],
      'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate  : moment()
  }, function (start, end) {
    window.alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
  })

      // The Calender
  $('#calendar').datetimepicker({
    format: 'L',
    inline: true
  })

  var jsonFromBE = @json($data);
  var result = Object.keys(jsonFromBE).map((key) => [key, jsonFromBE[key]]);
    var labels = [];
    var data = [];
    var backgroundColor = [];

    function randColorNum() {
        return Math.floor(Math.random() * 256);
    }

    function randomRGB() {
        return `rgb(${randColorNum()},${randColorNum()},${randColorNum()})`;
    }
    for (var i = 0; i < result.length; i++) {
        labels.push(result[i][0]);
        data.push(result[i][1]);
        backgroundColor.push(randomRGB());
    }
    console.log(backgroundColor);
//   var pieChartCanvas = $('#sales-chart-canvas').get(0).getContext('2d')
  var pieChartCanvas = document.getElementById('sales-chart-canvas').getContext('2d');
  var pieData = {
    labels,
    datasets: [
      {
        data,
        backgroundColor,
      }
    ]
  }
  var pieOptions = {
    legend: {
      display: false
    },
    maintainAspectRatio : false,
    responsive : true,
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  var pieChart = new Chart(pieChartCanvas, {
    type: 'doughnut',
    data: pieData,
    options: pieOptions
  });

})
</script>
@endsection
