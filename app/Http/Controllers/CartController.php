<?php

namespace App\Http\Controllers;

use App\Models\DiscountCoupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;

class CartController extends Controller
{
    public function addToCart(Request $request) {
        $product = Product::with('product_images')->find($request->id);
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy bản ghi'
            ]);
        }
        if (Cart::count() > 0) {
            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;

                }
            }

            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

                $status = false;
                $message = $product->title.' đã thêm vào giỏ hàng';
                // session()->flash('success', $message);
            } else {
                $status = false;
                $message = $product->title.' đã có trong giỏ hàng';
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<strong>'.$product->title.'</strong> đã thêm vào giỏ hàng';
            // session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'orderCount' => getOrderCount()
        ]);
    }

    public function cart() {
        $cartContent = Cart::content();
        $orderCount = Cart::count();
        $data['orderCount'] = $orderCount;
        $data['cartContent'] = $cartContent;
        $data['categorySelected'] = '';
        return view('frontend.cart', $data);
    }

    public function updateCart(Request $request) {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);

        if ($product->track_qty == "Yes") {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Giỏ hàng đã được cập nhật';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = 'Số lượng yêu cầu không có sẵn trong kho!';
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Giỏ hàn đã được cập nhật';
            $status = true;
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request) {
        $rowId = $request->rowId;
        $itemInfo = Cart::get($rowId);
        if ($itemInfo == null) {
            $message = 'Không tìm thấy sản phẩm trong giỏ hàng';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }
        Cart::remove($request->rowId);

        $message = 'Sản phẩm đã được xóa khỏi giỏ hàng';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkout() {
        $discount = 0;

        if (Cart::count() == 0) {
            return redirect()->route('frontend.cart');
        }

        if (Auth::check() == false) {
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
                return redirect()->route('frontend.checkout');
            }
            return redirect()->route('frontend.account.login',[
                'categorySelected' => ''
            ]);
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

        session()->forget('url.intended');

        $subTotal = Cart::subtotal(0,'','');
        //Apply discount
        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        $shipping = 15000;
        $grandTotal = ($subTotal - $discount) + $shipping;
        return view('frontend.checkout', [
            'customerAddress' => $customerAddress,
            'shipping' => number_format($shipping),
            'discount' => number_format($discount),
            'grandTotal' => number_format($grandTotal),
            'categorySelected' => ''
        ]);
    }

    public function processCheckout(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'email' => 'required|email',
            'address' => 'required|min:30',
            'city' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Something went wrong',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();

        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'mobile' => $request->mobile,
                'note' => $request->note,
            ],
        );

        if ($request->payment_method == 'cod') {
            $discountCodeId = '';
            $promoCode = '';
            $discount = 0;
            $shipping = 15000;
            $subTotal = Cart::subtotal(0,'','');

            //Apply discount
            if (session()->has('code')) {
                $code = session()->get('code');
                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount/100)*$subTotal;
                } else {
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }
            $grandTotal = ($subTotal + $shipping) - $discount;

            $order = new Order();
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->name = $request->name;
            $order->email = $request->email;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->mobile = $request->mobile;
            $order->notes = $request->order_notes;
            $order->reason_cancel = '';
            $order->save();

            foreach(Cart::content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();

                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;
                    $updateQty = $currentQty - $item->qty;
                    $productData->qty = $updateQty;
                    $productData->save();
                }
            }

            session()->flash('success', 'Bạn đã đặt hàng thành công');

            Cart::destroy();

            session()->forget('code');

            return response()->json([
                'message' => 'Order saved successfully',
                'orderId' => $order->id,
                'status' => true
            ]);
        }
    }

    public function thankyou() {
        return view('frontend.thanks',[
            'categorySelected' => ''
        ]);
    }

    public function getOrderSummary(Request $request) {
        $subTotal = Cart::subtotal(0,'','');
        $discount = 0;
        $shipping = 15000;
        $discountHtml = '';

        //Apply discount
        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            $discountHtml = '<div class="input-group mt-4" id="discount-response">
            <strong>'.session()->get('code')->code.'</strong><a class="btn btn-sm btn-danger" id="remove-discount"> <i class="fa fa-times"></i></a></div>';
        }


        $grandTotal = ($subTotal - $discount) + $shipping;
        return response()->json([
            'status' => true,
            'shipping' => number_format($shipping),
            'grandTotal' => number_format($grandTotal),
            'discountHtml' => $discountHtml,
            'discount' => number_format($discount),
        ]);
    }

    public function applyDiscount(Request $request) {
        $code = DiscountCoupon::where('code', $request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Mã giảm giá không hợp lệ'
            ]);
        }

        $now = Carbon::now();
        if ($code->starts_at != "") {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);

                if ($now->lt($startDate)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Mã giảm giá đã hết hạn'
                    ]);
                }
        }

        if ($code->expires_at != "") {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

                if ($now->gt($endDate)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Mã giảm giá đã hết hạn'
                    ]);
                }
        }

        //Max uses check
        if ($code->max_uses > 0) {
            $couponUsed = Order::where('coupon_code_id', $code->id)->count();

            if ($couponUsed >= $code->max_uses) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mã giảm giá này đã hết lượt sử dụng'
                ]);
            }
        }


        //Max uses user check
        if ($code->max_uses_user > 0) {
            $couponUsedByUser = Order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id])->count();

            if ($couponUsedByUser >= $code->max_uses_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn đã hết lượt sử dụng mã giảm giá này'
                ]);
            }
        }

        $subTotal = Cart::subtotal(0,'','');
        //Min amount condition check
        if ($code->min_amount > 0) {
            if ($subTotal < $code->min_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Đơn hàng của bạn cần đạt tối thiểu '.$code->min_amount.'đ',
                ]);
            }
        }


        session()->put('code', $code);

        return $this->getOrderSummary($request);
    }

    public function removeDiscount(Request $request) {
        session()->forget('code');
        return $this->getOrderSummary($request);
    }
}
