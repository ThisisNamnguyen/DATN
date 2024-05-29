<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\CustomerAddress;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    public function login() {
        return view('frontend.account.login',[
            'categorySelected' => ''
        ]);
    }

    public function register() {
        return view('frontend.account.register',[
            'categorySelected' => ''
        ]);
    }

    public function processRegister(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'Bạn đã đăng ký thành công');

            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }

                return redirect()->route('frontend.account.profile');

            } else {
                return redirect()->route('frontend.account.login')
                ->withInput($request->only('email'))
                ->with('error', 'Email hoặc mật khẩu của bạn không chính xác, vui lòng thử lại');
            }
        } else {
            return redirect()->route('frontend.account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile() {
        $userId = Auth::user()->id;

        $user = User::where('id', Auth::user()->id)->first();

        $address = CustomerAddress::where('user_id', $userId)->first();
        return view('frontend.account.profile',[
            'user' => $user,
            'address' => $address,
            'categorySelected' => ''
        ]);
    }

    public function updateProfile(Request $request) {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'phone' => 'required'
        ]);

        if ($validator->passes()) {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            session()->flash('success', 'Cập nhật hồ sơ thành công');
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật hồ sơ thành công'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateAddress(Request $request) {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'mobile' => 'required',
            'city' => 'required',
            'address' => 'required'
        ]);

        if ($validator->passes()) {
            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'city' => $request->city,
                    'address' => $request->address,
                ]
            );
            session()->flash('success', 'Cập nhật địa chỉ thành công');
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật địa chỉ thành công'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('frontend.account.login')
            ->with('success', 'Bạn đã đăng xuất');
    }

    public function orders() {
        $data = [];
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $data['orders'] = $orders;
        $data['categorySelected'] = '';
        return view('frontend.account.order', $data);
    }

    public function orderDetail($id) {
        $data = [];
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        $data['order'] = $order;

        $orderItems = OrderItem::where('order_id', $id)->get();
        $data['orderItems'] = $orderItems;

        $orderItemCount = OrderItem::where('order_id', $id)->count();
        $data['orderItemCount'] = $orderItemCount;
        $data['categorySelected'] = '';
        return view('frontend.account.order-detail', $data);
    }

    public function wishlist() {
        $wishlist =  Wishlist::where('user_id', Auth::user()->id)->get();
        $data = [];
        $data['wishlist'] = $wishlist;
        $data['categorySelected'] = '';
        return view('frontend.account.wishlist', $data);
    }

    public function removeProductFromWishlist(Request $request) {
        $wishlist =  Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
        if ($wishlist == null) {
            session()->flash('error', 'Sản phẩm đã được xóa khỏi danh sách');
            return response()->json([
                'status' => true
            ]);
        } else {
            Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->delete();
            session()->flash('success', 'Xóa sản phẩm khỏi danh sách thành công');
            return response()->json([
                'status' => true
            ]);
        }
    }

    public function showChangePasswordForm() {
        return view('frontend.account.change-password',[
            'categorySelected' => ''
        ]);
    }

    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->passes()) {
            $user = User::select('id', 'password')->where('id', Auth::user()->id)->first();

            if (!Hash::check($request->old_password, $user->password)) {
                session()->flash('error', 'Mật khẩu cũ của bạn không chính xác, vui lòng thử lại');
                return response()->json([
                    'status' => true
                ]);
            }

            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            session()->flash('success', 'Bạn đã đổi mật khẩu thành công');
            return response()->json([
                'status' => true,
                'message' => 'Bạn đã đổi mật khẩu thành công'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function forgotPassword() {
        return view('frontend.account.forgot-password',[
            'categorySelected' => ''
        ]);
    }

    public function cancelOrder($id) {
        $order = Order::where('id', $id)->first();

        return view('frontend.account.cancelOrder',[
            'categorySelected' => '',
            'order' => $order
        ]);
    }

    public function cancelReason(Request $request, $id) {
        $validator = Validator::make($request->all(),[
            'reason' => 'required',
        ]);

        if ($validator->passes()) {
            $order = Order::find($id);
            $order->reason_cancel = $request->reason;
            $order->save();

            session()->flash('success', 'Yêu cầu hủy đơn đã được gửi. Chúng tôi sẽ sớm phản hồi');
            return response()->json([
                'status' => true,
                'message' => 'Yêu cầu hủy đơn đã được gửi. Chúng tôi sẽ sớm phản hồi'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function changeOrderStatus($id) {
        $order = Order::find($id);
        $order->status = 'shipped';
        $order->save();

        $message = 'Tình trạng đơn hàng cập nhật thành công';
        session()->flash('success', 'Tình trạng đơn hàng cập nhật thành công');
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Tình trạng đơn hàng cập nhật thành công'
        // ]);
        return redirect()->route('frontend.account.orderDetail', $id)->with('success', $message);
    }

    public function processForgotPassword(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()->route('frontend.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        //Send mail
        $user = User::where('email', $request->email)->first();

        $formData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'Bạn có yêu cầu đặt lại mật khẩu'
        ];

        Mail::to($request->email)->send(new ResetPasswordMail($formData));

        $message = 'Kiểm tra hòm thư của bạn để đặt lại mật khẩu';
        return redirect()->route('frontend.forgotPassword')->with('success', $message);
    }

    public function resetPassword($token){
        $tokenExist = \DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenExist == null) {
            return redirect()->route('frontend.forgotPassword')->with('error', 'Invalid request');
        }

        return view('frontend.account.reset-password',[
            'token' => $token,
            'categorySelected' => ''
        ]);
    }

    public function processResetPassword(Request $request){
        $token = $request->token;

        $tokenObj = \DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenObj == null) {
            return redirect()->route('frontend.forgotPassword')->with('error', 'Invalid request');
        }

        $user = User::where('email', $tokenObj->email)->first();

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('frontend.resetPassword', $token)->withErrors($validator);
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('frontend.account.login')->with('success', 'Bạn đã thay đổi mật khẩu thành công');
    }
}
