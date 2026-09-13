<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * 商品一覧
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('sort_order')->get();

        $query = Product::with('category', 'productAccounts');

        // フィルタリング
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->input('keyword') . '%');
        }

        // ソート (デフォルトはおすすめ順)
        match ($request->input('sort', 'recommend')) {
            'new' => $query->latest(), // 新着順
            'price' => $query->orderBy('point'), // 価格順
            default => $query->orderByRaw('CASE WHEN popularity_order = 0 THEN 1 ELSE 0 END, popularity_order ASC'), // おすすめ順 (0は最後尾)
        };

        $products = $query->paginate(20)->withQueryString();

        $currentCategoryId = $request->integer('category_id') ?: null;

        return view('products.index', compact('products', 'categories', 'currentCategoryId'));
    }

    /**
     * 商品詳細
     *
     * @param Product $product
     * @return View
     */
    public function show(Product $product): View
    {
        $product->load('productAccounts');

        return view('products.show', compact('product'));
    }
}
