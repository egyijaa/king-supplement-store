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
        Schema::create('history_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('update_code')->nullable();
            $table->string('update_name')->nullable();
            $table->foreignId('category_id')->nullable()->constrained("category")->cascadeOnUpdate()->nullOnDelete();
            $table->string('update_qty')->nullable();
            $table->string('update_modal')->nullable();
            $table->string('update_price')->nullable();
            $table->string('update_price3')->nullable();
            $table->string('update_price6')->nullable();
            $table->string('update_in')->nullable();
            $table->string('update_out')->nullable();
            $table->string('old_code')->nullable();
            $table->string('old_name')->nullable();
            $table->foreignId('old_category_id')->nullable()->constrained("category")->cascadeOnUpdate()->nullOnDelete();
            $table->string('old_qty')->nullable();
            $table->string('old_modal')->nullable();
            $table->string('old_price')->nullable();
            $table->string('old_price3')->nullable();
            $table->string('old_price6')->nullable();
            $table->string('barang_masuk')->nullable();
            $table->string('barang_keluar')->nullable();
            $table->string('supplier_name')->nullable();
            $table->date('supplier_date')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->foreignId('product_id')->nullable()->constrained("product")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_products');
    }
};
