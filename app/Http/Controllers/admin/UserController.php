<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request) {
        $users = User::latest();

        if(!empty($request->get('keyword'))) {
            $users = $users->where('name', 'like', '%'.$request->get('keyword').'%');
            $users = $users->orWhere('email', 'like', '%'.$request->get('keyword').'%');
        }

        $users = $users->paginate(10);
        return view('admin.users.list',[
            'users' => $users
        ]);
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'password' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'phone' => 'required'
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;
            $user->password = Hash::make($request->password);
            $user->save();

            $message = 'Thêm người dùng thành công';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($userId, Request $request) {
        $user = User::find($userId);

        if (empty($user)) {
            $message = 'Không tìm thấy người dùng';
            session()->flash('error', $message);
            return redirect()->route('users.index');
        }

        return view('admin.users.edit',[
            'user' => $user
        ]);
    }

    public function update($userId, Request $request) {
        $user = user::find($userId);

        if (empty($user)) {
            $request->session()->flash('error', 'Không tìm thấy người dùng');
            return response()->json([
                "status" => false,
                'notFound' => true,
                'message' => 'Không tìm thấy người dùng'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'phone' => 'required',
        ]);

        if ($validator->passes()) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;
            if ($request->password != '') {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $message = 'Cập nhật người dùng thành công';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function changeRole(Request $request) {
        $user = User::find($request->id);
        $user->role = $request->status;
        $user->save();

        session()->flash('success', 'Thay đổi quyền thành công thành công');
        return response()->json([
            'status' => true
        ]);
    }

    public function delete($userId, Request $request) {
        $user = User::find($userId);

        if (empty($user)) {
            $message = 'Không tìm thấy người dùng';
            session()->flash('error', $message);
            return redirect()->route('users.index');
        }


        $user->delete();

        $request->session()->flash('success', 'Xóa người dùng thành công');

        return response()->json([
            'status' => true,
            'message' => 'Xóa người dùng thành công'
        ]);
    }
}
