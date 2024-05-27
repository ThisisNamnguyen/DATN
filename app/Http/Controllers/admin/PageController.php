<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request) {
        $pages = Page::latest();

        if(!empty($request->get('keyword'))) {
            $pages = Page::where('name', 'like', '%'.$request->get('keyword').'%');
        }

        $pages = $pages->paginate(10);
        return view('admin.pages.list', compact('pages'));
    }


    public function create() {
        return view('admin.pages.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'col_num' => 'required',
            'slug' => 'required|unique:pages',
        ]);

        if ($validator->passes()) {
            $page = new Page();
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->col_num = $request->col_num;
            $page->content = $request->content;
            $page->save();

            $request->session()->flash('success', 'Trang thêm thành công');
            return response()->json ([
                'status' => true,
                'message' => "Trang thêm thành công!",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($pageId, Request $request) {
        $page = Page::find($pageId);

        if (empty($page)) {
            return redirect()->route('pages.index');
        }

        return view('admin.pages.edit', compact('page'));
    }

    public function update($pageId, Request $request) {
        $page = Page::find($pageId);

        if (empty($page)) {
            $request->session()->flash('error', 'Không tìm thấy trang');
            return response()->json([
                "status" => false,
                'notFound' => true,
                'message' => 'Không tìm thấy trang'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'col_num' => 'required',
            'slug' => 'required|unique:pages,slug,'.$page->id.',id',
        ]);

        if ($validator->passes()) {
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->col_num = $request->col_num;
            $page->content = $request->content;
            $page->save();

            $request->session()->flash('success', 'Cập nhật trang thành công');
            return response()->json ([
                'status' => true,
                'message' => "Cập nhật trang thành công!",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function delete($pageId, Request $request) {
        $page = Page::find($pageId);
        if (empty($page)) {
            $request->session()->flash('error', 'Không tìm thấy trang');
            return response()->json([
                'status' => true,
                'message' => 'Không tìm thấy trang'
            ]);
        }

        $page->delete();

        $request->session()->flash('success', 'Xóa trang thành công');

        return response()->json([
            'status' => true,
            'message' => 'Xóa trang thành công'
        ]);
    }
}
