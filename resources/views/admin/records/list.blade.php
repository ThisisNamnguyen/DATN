@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    @include('admin.message')
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Nhập hàng</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.records.create')}}" class="btn btn-primary">Nhập hàng mới</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="card">
            <form action="" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route('admin.records.index')}}'" class="btn btn-default btn-sm">Reset</button>
                    </div>

                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                              </button>
                            </div>
                          </div>
                    </div>
                </div>
            </form>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Hóa đơn</th>
                            <th>Mã nhà cung cấp</th>
                            <th>Tên nhà cung cấp</th>
                            <th>Ngày nhập</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($records->isNotEmpty())
                            @foreach ($records as $record)
                            <tr>
                                <td><a href="{{ route('admin.records.detail', [$record->id]) }}">{{ $record->id }}</a></td>
                                {{-- <td>{{ $record->id}}</td> --}}
                                <td>{{ $record->supplier_code}}</td>
                                <td>{{ $record->supplier_name }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($record->created_at)->format('Y/m/d H:i:s')}}
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">Không tìm thấy bản ghi nào</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $records->links()}}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@section('customJS')
<script type="text/javascript">

</script>
@endsection
