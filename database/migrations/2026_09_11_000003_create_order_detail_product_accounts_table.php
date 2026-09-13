<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_detail_product_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_detail_id')->constrained('order_details')->cascadeOnDelete()->comment('注文明細ID');
            $table->foreignId('product_account_id')->constrained('product_accounts')->cascadeOnDelete()->comment('アカウント情報ID');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_detail_product_accounts');
    }
};
