<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function showChangePasswordForm(){
        return view('admin.change-password');
    }

    public function processChangePassword(Request $request) {
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        $id = Auth::guard('admin')->user()->id;
        $admin = User::where('id', $id)->first();
        if ($validator->passes()) {

            if (!Hash::check($request->old_password, $admin->password)) {
                session()->flash('error', 'Mật khẩu cũ của bạn không chính xác, vui lòng thử lại');
                return response()->json([
                    'status' => true
                ]);
            }

            User::where('id', $id)->update([
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
}
