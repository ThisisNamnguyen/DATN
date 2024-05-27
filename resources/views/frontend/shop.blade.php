@extends('frontend.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Cửa hàng</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h2>Danh mục</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                @if ($categories->isNotEmpty())
                                    @foreach ($categories as $key => $category)
                                        <div class="accordion-item">
                                            @if ($category->sub_category->isNotEmpty())
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $key}}" aria-expanded="false" aria-controls="collapseOne">
                                                    {{$category->name}}
                                                </button>
                                            </h2>
                                            @else
                                            <a href="{{ route("frontend.shop",$category->slug)}}" class="nav-item nav-link {{($categorySelected == $category->id) ? 'text-primary' : ''}}" >{{ $category->name}}</a>
                                            @endif

                                            @if ($category->sub_category->isNotEmpty())
                                            <div id="collapseOne-{{$key}}" class="accordion-collapse collapse {{($categorySelected == $category->id) ? 'show' : ''}}" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                                <div class="accordion-body">
                                                    <div class="navbar-nav">
                                                        @foreach ($category->sub_category as $subCategory)
                                                            <a href="{{route('frontend.shop',[$category->slug,$subCategory->slug])}}" class="nav-item nav-link {{($subCategorySelected == $subCategory->id) ? 'text-primary' : ''}}">{{ $subCategory->name}}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Price</h3>
                    </div>

                    <div class="card">
                        <div class="card-body price-change">
                            <input type="text" class="js-range-slider" name="my-range" value="">
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">
                                    <select name="sort" id="sort" class="form-control">
                                        <option value="latest" {{($sort == 'latest') ? 'selected' : ''}}>Mới nhất</option>
                                        <option value="price_desc" {{($sort == 'price_desc') ? 'selected' : ''}}>Từ cao đến thấp</option>
                                        <option value="price_asc {{($sort == 'price_asc') ? 'selected' : ''}}">Từ thấp đến cao</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if ($products->isNotEmpty())
                            @foreach ($products as $product)
                            @php
                                $productImage = $product->product_images->first();
                            @endphp
                            <div class="col-md-4">
                                <div class="card product-card">
                                    <div class="product-image position-relative">

                                        <a href="{{ route("frontend.product",$product->slug)}}" class="product-img">
                                        @if (!empty($productImage->image))
                                            <img src="{{ asset('/uploads/product/small/'.$productImage->image)}}" class="card-img-top" ></td>
                                        @else
                                            <img src="{{ asset('admin-assets/img/default-150x150.png')}}" class="card-img-top " ></td>
                                        @endif
                                        </a>
                                        <a onclick="addToWishlist( {{$product->id}} )" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>

                                        <div class="product-action">
                                            @if ($product->track_qty == 'Yes')
                                                @if($product->qty > 0)
                                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                                    <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                                                </a>
                                                @else
                                                <a class="btn btn-dark" href="javascript:void(0);">
                                                    <i class="fa fa-shopping-cart"></i> Hết hàng
                                                </a>
                                                @endif
                                        @else
                                        <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                            <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                                        </a>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="card-body text-center mt-3">
                                        <a class="h6 link" href="product.php">{{$product->title}}</a>
                                        <div class="price mt-2">
                                            <span class="h5"><strong>{{number_format($product->price)}}đ</strong></span>
                                        @if($product->compare_price > 0)
                                            <span class="h6 text-underline"><del>{{number_format($product->compare_price)}}đ</del></span>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif

                        <div class="col-md-12 pt-5">
                            {{$products->withQueryString()->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('customJS')
<script>
    rangeSlider = $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: 0,
        max: 1000000,
        from: {{$priceMin}},
        step: 10000,
        to: {{$priceMax}},
        skin: "round",
        max_postfix: "đ",
        prefix: "",
        onFinish: function() {
            apply_filters()
        }
    });

    var slider = $(".js-range-slider").data("ionRangeSlider");

    $('#sort').change(function(){
        apply_filters();
    });

    function apply_filters() {
        var url = '{{url()->current() }}?';
        url += '&price_min=' + slider.result.from + '&price_max=' + slider.result.to;

        //Sorting
        var keyword = $('#search').val();

        if (keyword.length > 0) {
            url += '&search=' + keyword;
        }

        url += '&sort=' + $("#sort").val();
        window.location.href = url.toString();
    }

</script>
@endsection
