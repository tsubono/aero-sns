<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('order_details', 'user_info_text')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropColumn('user_info_text');
            });
        }
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->text('user_info_text')->nullable()->comment('アカウント情報(購入時点のスナップショット)');
        });
    }
};
