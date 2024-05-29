<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ImportItem;
use App\Models\importProduct;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\TempImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index() {
        $totalOrders = Order::where('status', '!=' , 'cancelled')->count();
        $totalProducts = Product::count();
        $totalUsers = User::where('role', 1)->count();
        $totalRating = ProductRating::count();

        $totalRevenue = Order::where('status', 'shipped')->sum('grand_total');

        //This month revenue
        $startOfMonth =  Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        $revenueThisMonth = Order::where('status', 'shipped')
                            ->whereDate('created_at', '>=', $startOfMonth)
                            ->whereDate('created_at', '<=', $currentDate)
                            ->sum('grand_total');

        // //Last month revenue
        // $lastMonthStartDate =  Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        // $lastMonthEndDate =  Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

        // $revenueLastMonth = Order::where('status', '!=', 'cancelled')
        //                     ->whereDate('created_at', '>=', $lastMonthStartDate)
        //                     ->whereDate('created_at', '<=', $lastMonthEndDate)
        //                     ->sum('grand_total');
        //Profit total
        $totalImportMoney = ImportItem::sum('total_price');

        //Delete temp images
        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');

        $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get();

        foreach($tempImages as $tempImage) {
            $path = public_path('/temp/'.$tempImage->name);
            $thumbPath = public_path('/temp/thumb/'.$tempImage->name);

            //Delete Main Image
            if (File::exists($path)) {
                File::delete($path);
            }

            //Delete Thumb Imabge
            if (File::exists($thumbPath)) {
                File::delete($thumbPath);
            }
        }

        //Pie chart
        $data = array();

        $categories = Category::get();
        foreach($categories as $key => $category) {
            $productCount = Product::where('category_id', $category->id)->count();
            $key = $category->name;
            $data[$key] = $productCount;
        }
        return view('admin.dashboard',[
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalRating' => $totalRating,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'totalImportMoney' => $totalImportMoney,
            'profit' => $totalRevenue - $totalImportMoney,
            'productCount' => $productCount,
            'data' => $data
        ]);
    }


    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
