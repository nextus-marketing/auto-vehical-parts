<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('ACTIVE');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('index')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('part_number')->nullable();
            $table->string('warranty')->nullable()->default('1-Year Comprehensive Replacement Guarantee');
            $table->string('mileage')->nullable();
            $table->text('fitment')->nullable();
            $table->string('stock_status')->default('IN_STOCK');
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('reviews_count')->default(10);
            $table->string('home_featured')->default('INACTIVE');
            $table->string('photo')->nullable();
            $table->json('other_photo')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
