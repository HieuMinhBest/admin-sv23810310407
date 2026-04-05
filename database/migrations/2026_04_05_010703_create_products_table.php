<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sv23810310407_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('sv23810310407_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('stock_quantity')->default(0);
            $table->string('image_path')->nullable();
            $table->enum('status', ['draft', 'published', 'out_of_stock'])->default('draft');
            
            // Trường sáng tạo tự chọn: Phần trăm giảm giá
            $table->integer('discount_percent')->nullable()->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sv23810310407_products');
    }
};
