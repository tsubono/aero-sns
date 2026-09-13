<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class TopController extends Controller
{
    /**
     * TOP
     *
     * @return View
     */
    public function index(): View
    {
        $categories = Category::orderBy('sort_order')->get();

        $productsByCategory = $categories->mapWithKeys(function ($category) {
            return [
                $category->id => $category->products()
                    ->with('productAccounts')
                    ->latest()
                    ->limit(8)
                    ->get(),
            ];
        });

        return view('top', compact('categories', 'productsByCategory'));
    }
}
