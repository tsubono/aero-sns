<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->comment('注文ID');
            $table->foreignId('product_id')->nullable()
                ->constrained('products')->nullOnDelete()->comment('商品ID');
            $table->string('product_name')->comment('商品名(購入時点のスナップショット)');
            $table->unsignedInteger('point')->comment('ポイント(購入時点のスナップショット)');
            $table->unsignedInteger('quantity')->default(1)->comment('数量');
            $table->text('user_info_text')->nullable()->comment('アカウント情報(購入時点のスナップショット)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
