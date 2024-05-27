<?php

use App\Models\Category;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Page;
use App\Models\ProductImage;

function getCategories() {
    return Category::orderBy('name', 'ASC')
            ->with('sub_category')
            ->orderBy('id', 'DESC')
            ->where('status', 1)
            ->get();
}

function getOrderCount() {
    $orderCount = Cart::count();

    return $orderCount;
}

function getProductImage($productId) {
    return ProductImage::where('product_id', $productId)->first();
}

function staticPages() {
    $pages = Page::orderBy('name', 'ASC')->get();
    return $pages;
}
?>
