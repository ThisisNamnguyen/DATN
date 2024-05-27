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
        Schema::create('import_product', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_code');
            $table->string('supplier_name');
            $table->string('barcode');
            $table->string('product_name');
            $table->integer('qty');
            $table->double('import_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_product');
    }
};
