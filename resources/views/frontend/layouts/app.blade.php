<!DOCTYPE html>
<html class="no-js" lang="en_AU" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Laravel shop online</title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

	<meta name="HandheldFriendly" content="True" />
	<meta name="pinterest" content="nopin" />

	<meta property="og:locale" content="en_AU" />
	<meta property="og:type" content="website" />
	<meta property="fb:admins" content="" />
	<meta property="fb:app_id" content="" />
	<meta property="og:site_name" content="" />
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />
	<meta property="og:image:type" content="image/jpeg" />
	<meta property="og:image:width" content="" />
	<meta property="og:image:height" content="" />
	<meta property="og:image:alt" content="" />

	<meta name="twitter:title" content="" />
	<meta name="twitter:site" content="" />
	<meta name="twitter:description" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:image:alt" content="" />
	<meta name="twitter:card" content="summary_large_image" />
    <meta name="csrf-token" content="{{ csrf_token()}}">

	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/css/slick-theme.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/css/video-js.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css')}}" />

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">

    <!-- Fav Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="#" />

</head>
<body data-instant-intensity="mousedown">
<div class="bg-light top-header">
	<div class="container">
		<div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
			<div class="col-lg-4 logo">
				<a href="{{route('frontend.homepage')}}" class="text-decoration-none">
					<span class="h1 text-uppercase text-primary bg-dark px-2">Online</span>
					<span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">SHOP</span>
				</a>
			</div>
			<div class="col-lg-6 col-6 text-left  d-flex justify-content-end align-items-center">
				@if(Auth::check())
                <a href="{{route('frontend.account.profile')}}" class="nav-link text-dark">Tài khoản</a>
				@else
                    <a href="{{route('frontend.account.login')}}" class="nav-link text-dark btn btn-warning ml-4" style="margin-right: 10px">Đăng nhập</a>
                @endif
                <form action="{{ route('frontend.shop')}}" method="get">
					<div class="input-group">
						<input value="{{ Request::get('search') }}" id="search" name="search" type="text" placeholder="Tìm kiếm sản phẩm" class="form-control" aria-label="Amount (to the nearest dollar)">
						<button type="submit" class="input-group-text">
							<i class="fa fa-search"></i>
					  	</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<header class="bg-dark">
	<div class="container">
		<nav class="navbar navbar-expand-xl" id="navbar">
			<a href="index.php" class="text-decoration-none mobile-logo">
				<span class="h2 text-uppercase text-primary bg-dark">Online</span>
				<span class="h2 text-uppercase text-white px-2">SHOP</span>
			</a>
			<button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      			<!-- <span class="navbar-toggler-icon icon-menu"></span> -->
				  <i class="navbar-toggler-icon fas fa-bars"></i>
    		</button>
    		<div class="collapse navbar-collapse" id="navbarSupportedContent">
      			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
        			<!-- <li class="nav-item">
          				<a class="nav-link active" aria-current="page" href="index.php" title="Products">Home</a>
        			</li> -->
                    @if(getCategories()->isNotEmpty())
                    @foreach (getCategories() as $category)
                        <li class="nav-item dropdown">
                            @if ($category->sub_category->isNotEmpty())
                            <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                {{$category->name}}
                            </button>
                            @else
                            <a href="{{ route("frontend.shop",$category->slug)}}" class="nav-item nav-link {{($categorySelected == $category->id) ? 'text-primary' : ''}}" >{{ $category->name}}</a>
                            @endif
                            @if($category->sub_category->isNotEmpty())
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    @foreach ($category->sub_category as $subCategory)
                                        <li><a class="dropdown-item nav-link" href="{{ route('frontend.shop', [$category->slug,$subCategory->slug])}}">{{$subCategory->name}}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                    @endif
      			</ul>
      		</div>
			<div class="right-nav py-0">
				<a href="{{ route('frontend.cart')}}" class="ml-3 d-flex pt-2" id="cart-number-change">
					<i class="fas fa-shopping-cart text-primary"></i>
                    <div id="number-change">
                        @if (getOrderCount() > 0)
                            <span class="badge badge-pill badge-warning">{{ getOrderCount() }}</span>
                        @endif
                    </div>
				</a>
			</div>
      	</nav>
  	</div>
</header>
@yield('content')
<footer class="bg-dark mt-5">
	<div class="container pb-5 pt-3">
		<div class="row">
			<div class="col-md-4">
				<div class="footer-card">
					<h3>Thông tin liên lạc</h3>
					<p>Email: abc@gmail.com<br>
					Địa chỉ: 209 Khuất Duy Tiến Thanh Xuân Hà Nội<br>
					Hotline: 0123456787<br>
				</div>
			</div>

			<div class="col-md-4">
				<div class="footer-card">
					<h3>Công ty</h3>
					<ul>
                        @if (staticPages()->isNotEmpty())
                            @foreach (staticPages() as $page)
                            @if ($page->col_num == 1)
                                <li><a href="{{ route('frontend.page',$page->slug)}}" title="{{ $page->name}}">{{ $page->name}}</a></li>
                            @endif
                            @endforeach
                        @endif
					</ul>
				</div>
			</div>

			<div class="col-md-4">
				<div class="footer-card">
					<h3>Dịch vụ khách hàng</h3>
					<ul>
                        @if (staticPages()->isNotEmpty())
                            @foreach (staticPages() as $page)
                            @if ($page->col_num == 2)
                                <li><a href="{{ route('frontend.page',$page->slug)}}" title="{{ $page->name}}">{{ $page->name}}</a></li>
                            @endif
                            @endforeach
                        @endif
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="copyright-area">
		<div class="container">
			<div class="row">
				<div class="col-12 mt-3">
					<div class="copy-right text-center">
						{{-- <p>© Copyright 2022 Amazing Shop. All Rights Reserved</p> --}}
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

<!-- Wishlist Modal -->
<div class="modal fade" id="wishlistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        </div>
        <div class="modal-body">

        </div>
      </div>
    </div>
  </div>

<script src="{{ asset('front-assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js')}}"></script>
<script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js')}}"></script>
<script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js')}}"></script>
<script src="{{ asset('front-assets/js/slick.min.js')}}"></script>
<script src="{{ asset('front-assets/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{ asset('front-assets/js/custom.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
window.onscroll = function() {myFunction()};

// Get the navbar
var navbar = document.querySelector("header.bg-dark");

// Get the offset position of the navbar
var sticky = navbar.offsetTop;

// Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function addToCart(id) {
        $.ajax({
            url: '{{ route("frontend.addToCart")}}',
            type: 'post',
            data: {id:id},
            dataType: 'json',
            success: function(response) {
                if (response.status == true) {
                    window.location.href="{{route('frontend.cart')}}";
                    $('#number-change').html(`<span class="badge badge-pill badge-warning">${response['orderCount']}</span>`)
                } else {
                    alert(response.message);
                    $('#number-change').html(`<span class="badge badge-pill badge-warning">${response['orderCount']}</span>`)
                    console.log(response['orderCount']);
                }
            }
        });
    }

    function addToWishlist(id) {
        $.ajax({
            url: '{{ route("frontend.addToWishlist")}}',
            type: 'post',
            data: {id:id},
            dataType: 'json',
            success: function(response) {
                if (response.status == true) {
                    $('#wishlistModal .modal-body').html(response.message);
                    $('#wishlistModal').modal('show');
                } else {
                    window.location.href="{{route('frontend.account.login')}}";
                }
            }
        });
    }

</script>

@yield('customJS')
</body>
</html>
