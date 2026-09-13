<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()
                ->constrained('categories')->nullOnDelete()->comment('カテゴリID');
            $table->string('name')->comment('商品名');
            $table->unsignedInteger('point')->comment('ポイント');
            $table->text('user_info_text')->comment('ユーザー情報テキスト')->nullable();
            $table->text('description')->comment('商品内容')->nullable();
            $table->text('contents')->comment('収録内容')->nullable();
            $table->string('file_type')->comment('ファイル形式(PNG or ZIP)')->nullable();
            $table->string('thumbnail_path')->comment('サムネイル画像パス')->nullable();
            $table->unsignedInteger('stock')->default(1)->comment('在庫数');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
