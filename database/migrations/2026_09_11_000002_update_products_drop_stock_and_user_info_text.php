<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock', 'user_info_text']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('user_info_text')->nullable()->comment('ユーザー情報テキスト');
            $table->unsignedInteger('stock')->default(1)->comment('在庫数');
        });
    }
};
