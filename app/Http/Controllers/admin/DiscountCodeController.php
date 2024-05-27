<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class DiscountCodeController extends Controller
{
    public function index(Request $request) {
        $discountCoupons = DiscountCoupon::latest();

        if(!empty($request->get('keyword'))) {
            $discountCoupons = DiscountCoupon::where('name', 'like', '%'.$request->get('keyword').'%');
            $discountCoupons = DiscountCoupon::orWhere('code', 'like', '%'.$request->get('keyword').'%');
        }

        $discountCoupons = $discountCoupons->paginate(10);
        return view('admin.coupon.list', compact('discountCoupons'));
    }

    public function create() {
        return view('admin.coupon.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            if (!empty($request->starts_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Ngày bắt đầu không được kém thời gian hiện tại']
                    ]);
                }
            }

            if (!empty($request->starts_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Ngày bắt đầu không được kém thời gian hiện tại']
                    ]);
                }
            }

            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expireAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);

                if ($expireAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Ngày hết hạn phải sau ngày bắt đầu']
                    ]);
                }
            }

            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $request->session()->flash('success', 'Thêm mã giảm giá thành công');
            return response()->json ([
                'status' => true,
                'message' => "Thêm mã giảm giá thành công!",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $id) {
        $coupon = DiscountCoupon::find($id);

        if ($coupon == null) {
            session()->flash('error', 'Không tìm thấy bản ghi');
            return redirect()->route('coupons.index');
        }

        $data['coupon'] = $coupon;
        return view('admin.coupon.edit', $data);
    }

    public function update(Request $request, $id) {
        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null) {
            session()->flash('error', 'Không tìm thấy bản ghi');
            return response()->json([
                "status" => true,
            ]);
        }

        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expireAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);

                if ($expireAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Ngày hết hạn phải sau ngày bắt đầu']
                    ]);
                }
            }

            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $request->session()->flash('success', 'Mã giảm giá được cập nhật thành công');
            return response()->json ([
                'status' => true,
                'message' => "Mã giảm giá được cập nhật thành công!",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function delete(Request $request, $id) {
        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null) {
            session()->flash('error', 'Không tìm thấy bản ghi');
            return response()->json([
                "status" => true,
            ]);
        }

        $discountCode->delete();
        session()->flash('success', 'Xóa mã giảm giá thành công');
            return response()->json([
                "status" => true,
            ]);
    }
}
