<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;

class FrontController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null) {
        $categorySelected = '';
        $subCategorySelected = '';
        $products = Product::where('is_featured', 'Yes')->where('status', 1)->orderBy('id', 'DESC')->get();
        $data['featuredProducts'] = $products;

        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            $products = $products->where('sub_category_id', $subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        $latestProducts = Product::orderBy('id', 'DESC')->where('status', 1)->take(8)->get();
        $data['latestProducts'] = $latestProducts;

        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        return view('frontend.homepage', $data);
    }

    public function addToWishlist(Request $request) {
        if (Auth::check() == false) {

            session(['url.intended' => url()->previous()]);

            return response()->json([
                'status' => false,
            ]);
        }

        $product = Product::where('id', $request->id)->first();
        if ($product == null) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Không tìm thấy sản phẩm</div>'
            ]);
        }

        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ]
        );
        // $whislist = new Wishlist();
        // $whislist->user_id = Auth::user()->id;
        // $whislist->product_id = $request->id;
        // $whislist->save();


        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>' .$product->title. '</strong> đã thêm vào yêu thích</div>'
        ]);
    }

    public function page($slug) {
        $page = Page::where('slug', $slug)->first();
        if ($page == null) {
            abort(404);
        }
        return view('frontend.page',[
            'page' => $page,
            'categorySelected' => ''
        ]);
    }
}
