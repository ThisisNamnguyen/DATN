<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src=" {{ asset('admin-assets/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">LARAVEL SHOP</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href=" {{ route('admin.dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Trang chủ admin</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href=" {{ route('categories.index')}} " class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Quản lý danh mục</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sub-categories.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Quản lý danh mục con</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.records.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-upload"></i>
                        <p>Quản lý nhập hàng</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>Quản lý sản phẩm</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.productRatings')}}" class="nav-link">
                        <i class="nav-icon  fas fa-star"></i>
                        <p>Quản lý đánh giá</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('orders.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>Quản lý đơn hàng</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('coupons.index')}}" class="nav-link">
                        <i class="nav-icon  fa fa-percent" aria-hidden="true"></i>
                        <p>Quản lý mã giảm giá</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index')}}" class="nav-link">
                        <i class="nav-icon  fas fa-users"></i>
                        <p>Quản lý người dùng</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pages.index') }}" class="nav-link">
                        <i class="nav-icon  far fa-file-alt"></i>
                        <p>Quản lý các trang điều hướng</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
 </aside>
