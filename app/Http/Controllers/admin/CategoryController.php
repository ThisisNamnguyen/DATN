<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class CategoryController extends Controller
{
    public function index(Request $request) {
        $categories = Category::latest();

        if(!empty($request->get('keyword'))) {
            $categories = Category::where('name', 'like', '%'.$request->get('keyword').'%');
        }

        $categories = $categories->paginate(10);
        return view('admin.category.list', compact('categories'));
    }


    public function create() {
        return view('admin.category.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            //Save image
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);

                //Generate thumbnail
                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($sPath);
                $image->scale(width:450);
                $image->save($dPath);

                $category->image = $newImageName;
                $category->save();
            }

            $request->session()->flash('success', 'Danh mục thêm thành công');
            return response()->json ([
                'status' => true,
                'message' => "Danh mục thêm thành công!",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($categoryId, Request $request) {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.index');
        }

        return view('admin.category.edit', compact('category'));
    }

    public function update($categoryId, Request $request) {
        $category = Category::find($categoryId);

        if (empty($category)) {
            $request->session()->flash('error', 'Không tìm thấy danh mục');
            return response()->json([
                "status" => false,
                'notFound' => true,
                'message' => 'Không tìm thấy danh mục'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $oldImage = $category->image;
            //Save image
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);

                //Generate thumbnail
                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($sPath);
                $image->scale(width:450);
                $image->save($dPath);

                $category->image = $newImageName;
                $category->save();

                //Delete old image
                File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/category/'.$oldImage);

            }

            $request->session()->flash('success', 'Cập nhật danh mục thành công');
            return response()->json ([
                'status' => true,
                'message' => "Cập nhật danh mục thành công!",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function delete($categoryId, Request $request) {
        $category = Category::find($categoryId);
        if (empty($category)) {
            $request->session()->flash('error', 'Không tìm thấy danh mục');
            return response()->json([
                'status' => true,
                'message' => 'Không tìm thấy danh mục'
            ]);
        }

        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);

        $category->delete();

        $request->session()->flash('success', 'Xóa danh mục thành công');

        return response()->json([
            'status' => true,
            'message' => 'Xóa danh mục thành công'
        ]);
    }
}
