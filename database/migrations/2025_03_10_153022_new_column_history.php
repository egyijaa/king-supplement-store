<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('history_products', function (Blueprint $table) {
            //
            $table->foreignId('trans_id')->nullable()->constrained("transaction")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('supply_id')->nullable()->constrained("supply")->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_products', function (Blueprint $table) {
            //
            $table->dropForeign(['trans_id']);
            $table->dropColumn('trans_id');
            $table->dropForeign(['supply_id']);
            $table->dropColumn('supply_id');
        });
    }
};
