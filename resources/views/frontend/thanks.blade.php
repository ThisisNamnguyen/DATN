@extends('frontend.layouts.app')

@section('content')
<section class="container">
    <div class="col-md-12 text-center py-5">
        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success')}}
        </div>
        @endif
        <h1>Cảm ơn bạn đã đặt hàng! Chúng tôi sẽ sớm xử lý đơn hàng của bạn.</h1>
    </div>
</section>
@endsection
