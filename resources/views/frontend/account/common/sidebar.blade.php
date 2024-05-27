<ul id="account-panel" class="nav nav-pills flex-column" >
    <li class="nav-item">
        <a href="{{route('frontend.account.profile')}}"  class="nav-link font-weight-bold" role="tab" aria-controls="tab-login" aria-expanded="false"><i class="fas fa-user-alt"></i> Hồ sơ của tôi</a>
    </li>
    <li class="nav-item">
        <a href="{{route('frontend.account.orders')}}"  class="nav-link font-weight-bold" role="tab" aria-controls="tab-register" aria-expanded="false"><i class="fas fa-shopping-bag"></i> Đơn hàng</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('frontend.account.wishlist') }}"  class="nav-link font-weight-bold" role="tab" aria-controls="tab-register" aria-expanded="false"><i class="fas fa-heart"></i> Yêu thích</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('frontend.account.changePassword') }}"  class="nav-link font-weight-bold" role="tab" aria-controls="tab-register" aria-expanded="false"><i class="fas fa-lock"></i> Đổi mật khẩu</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('frontend.account.logout')}}" class="nav-link font-weight-bold" role="tab" aria-controls="tab-register" aria-expanded="false"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
    </li>
</ul>
