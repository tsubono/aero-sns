<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->comment('ユーザーID');
            $table->unsignedInteger('point')->comment('付与ポイント数');
            $table->unsignedInteger('amount')->comment('決済金額(円)');
            $table->string('payment_method')->default('credit_card')->comment('決済方法');
            $table->tinyInteger('status')->comment('ステータス(1:処理中 2:完了 3:失敗)');
            $table->string('transaction_id')->nullable()->comment('決済代行会社の取引ID');
            $table->timestamp('charged_at')->nullable()->comment('チャージ日時');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_charges');
    }
};
