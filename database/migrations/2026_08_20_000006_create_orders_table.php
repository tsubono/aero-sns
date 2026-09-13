<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->comment('注文番号');
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->comment('ユーザーID');
            $table->unsignedInteger('total_point')->comment('合計消費ポイント');
            $table->tinyInteger('status')->default(1)->comment('ステータス(1:完了)');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
