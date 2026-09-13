<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\UpsertRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * 商品管理
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $products = Product::with('category', 'productAccounts')->withCount('orderDetails')
            ->when($request->filled('keyword'), fn($query) => $query->where('name', 'like', '%' . $request->input('keyword') . '%'))
            ->when($request->filled('category_id'), fn($query) => $query->where('category_id', $request->input('category_id')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $categories = Category::orderBy('id')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * 登録フォーム
     *
     * @return View
     */
    public function create(): View
    {
        $categories = Category::orderBy('id')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * 登録処理
     *
     * @param UpsertRequest $request
     * @return RedirectResponse
     */
    public function store(UpsertRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // サムネイル画像アップロード
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('products', 'public');
        }

        // アカウント情報取得
        $accountTexts = $data['account_texts'] ?? [];
        unset($data['thumbnail'], $data['account_texts']);

        // 商品登録
        $product = Product::create($data);
        // 商品アカウント情報登録
        $this->syncAccountTexts($product, $accountTexts);

        return redirect()->route('admin.products.index')->with('success', '商品を登録しました。');
    }

    /**
     * 編集フォーム
     *
     * @param Product $product
     * @return View
     */
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('id')->get();
        $product->load('productAccounts');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * 更新処理
     *
     * @param UpsertRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(UpsertRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        // サムネイル画像アップロード
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail_path) {
                Storage::disk('public')->delete($product->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('products', 'public');
        }

        // アカウント情報取得
        $accountTexts = $data['account_texts'] ?? [];
        unset($data['thumbnail'], $data['account_texts']);

        // 商品更新
        $product->update($data);
        // 商品アカウント情報更新
        $this->syncAccountTexts($product, $accountTexts);

        return redirect()->route('admin.products.index')->with('success', '商品を更新しました。');
    }

    /**
     * 削除処理
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', '商品を削除しました。');
    }

    /**
     * 商品アカウント情報登録・更新
     *
     * @param Product $product
     * @param array $accountTexts
     * @return void
     */
    private function syncAccountTexts(Product $product, array $accountTexts): void
    {
        $existingIds = []; // 既存ID
        $newTexts = []; // 新規アカウント情報

        foreach ($accountTexts as $item) {
            if (isset($item['id']) && $item['id']) {
                $existingIds[$item['id']] = $item['text'];
            } else {
                $text = trim($item['text'] ?? '');
                if ($text !== '') {
                    $newTexts[] = $text;
                }
            }
        }

        // 未使用で既存IDに含まれないものを削除
        $product->productAccounts()
            ->where('is_used', false)
            ->whereNotIn('id', array_keys($existingIds))
            ->delete();

        // 既存IDのテキストを更新
        foreach ($existingIds as $id => $text) {
            ProductAccount::where('id', $id)
                ->where('product_id', $product->id)
                ->where('is_used', false)
                ->update(['account_text' => $text]);
        }

        // 新規追加
        foreach ($newTexts as $text) {
            $product->productAccounts()->create(['account_text' => $text]);
        }
    }
}
