<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->comment('ユーザーID');
            $table->tinyInteger('type')->comment('種別(1:チャージ 2:購入消費 3:返却)');
            $table->integer('point')->comment('増減値(符号付き)');
            $table->string('related_type')->nullable()->comment('関連元タイプ(ポリモーフィック)');
            $table->unsignedBigInteger('related_id')->nullable()->comment('関連元ID');
            $table->unsignedInteger('balance_after')->comment('処理後のポイント残高');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};
