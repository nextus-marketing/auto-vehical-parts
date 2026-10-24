<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Ensure Categories columns
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'image')) {
                    $table->string('image')->nullable()->after('index');
                }
            });
        }

        // 2. Ensure Products columns
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'price')) {
                    $table->decimal('price', 10, 2)->default(0.00)->after('category_id');
                }
                if (!Schema::hasColumn('products', 'compare_price')) {
                    $table->decimal('compare_price', 10, 2)->nullable()->after('price');
                }
                if (!Schema::hasColumn('products', 'short_description')) {
                    $table->text('short_description')->nullable()->after('compare_price');
                }
                if (!Schema::hasColumn('products', 'part_number')) {
                    $table->string('part_number')->nullable()->after('description');
                }
                if (!Schema::hasColumn('products', 'warranty')) {
                    $table->string('warranty')->nullable()->default('1-Year Comprehensive Replacement Guarantee')->after('part_number');
                }
                if (!Schema::hasColumn('products', 'mileage')) {
                    $table->string('mileage')->nullable()->after('warranty');
                }
                if (!Schema::hasColumn('products', 'fitment')) {
                    $table->text('fitment')->nullable()->after('mileage');
                }
                if (!Schema::hasColumn('products', 'stock_status')) {
                    $table->string('stock_status')->default('IN_STOCK')->after('fitment');
                }
                if (!Schema::hasColumn('products', 'rating')) {
                    $table->decimal('rating', 3, 2)->default(5.00)->after('stock_status');
                }
                if (!Schema::hasColumn('products', 'reviews_count')) {
                    $table->integer('reviews_count')->default(10)->after('rating');
                }
            });
        }

        // 3. Ensure Coupons table
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('title')->nullable();
                $table->string('discount_type')->default('PERCENTAGE'); // PERCENTAGE, FIXED
                $table->decimal('discount_value', 10, 2);
                $table->decimal('min_order_amount', 10, 2)->default(0.00);
                $table->decimal('max_discount_amount', 10, 2)->nullable();
                $table->date('start_date')->nullable();
                $table->date('expires_at')->nullable();
                $table->integer('usage_limit')->nullable();
                $table->integer('used_count')->default(0);
                $table->string('status')->default('ACTIVE');
                $table->text('description')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('coupons', function (Blueprint $table) {
                if (!Schema::hasColumn('coupons', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('description');
                }
                if (!Schema::hasColumn('coupons', 'updated_by')) {
                    $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                }
            });
        }

        // 4. Ensure Orders columns
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('notes');
                }
                if (!Schema::hasColumn('orders', 'updated_by')) {
                    $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                }
                if (!Schema::hasColumn('orders', 'order_number')) {
                    $table->string('order_number')->nullable()->after('id');
                }
                if (!Schema::hasColumn('orders', 'customer_name')) {
                    $table->string('customer_name')->nullable()->after('order_number');
                }
                if (!Schema::hasColumn('orders', 'customer_email')) {
                    $table->string('customer_email')->nullable()->after('customer_name');
                }
                if (!Schema::hasColumn('orders', 'customer_phone')) {
                    $table->string('customer_phone')->nullable()->after('customer_email');
                }
                if (!Schema::hasColumn('orders', 'shipping_address')) {
                    $table->string('shipping_address')->nullable()->after('customer_phone');
                }
                if (!Schema::hasColumn('orders', 'city')) {
                    $table->string('city')->nullable()->after('shipping_address');
                }
                if (!Schema::hasColumn('orders', 'state')) {
                    $table->string('state')->nullable()->after('city');
                }
                if (!Schema::hasColumn('orders', 'zip_code')) {
                    $table->string('zip_code')->nullable()->after('state');
                }
                if (!Schema::hasColumn('orders', 'country')) {
                    $table->string('country')->default('USA')->after('zip_code');
                }
                if (!Schema::hasColumn('orders', 'vin_number')) {
                    $table->string('vin_number')->nullable()->after('country');
                }
                if (!Schema::hasColumn('orders', 'vehicle_details')) {
                    $table->string('vehicle_details')->nullable()->after('vin_number');
                }
                if (!Schema::hasColumn('orders', 'subtotal')) {
                    $table->decimal('subtotal', 10, 2)->default(0.00)->after('vehicle_details');
                }
                if (!Schema::hasColumn('orders', 'coupon_code')) {
                    $table->string('coupon_code')->nullable()->after('subtotal');
                }
                if (!Schema::hasColumn('orders', 'discount_amount')) {
                    $table->decimal('discount_amount', 10, 2)->default(0.00)->after('coupon_code');
                }
                if (!Schema::hasColumn('orders', 'shipping_amount')) {
                    $table->decimal('shipping_amount', 10, 2)->default(0.00)->after('discount_amount');
                }
                if (!Schema::hasColumn('orders', 'tax_amount')) {
                    $table->decimal('tax_amount', 10, 2)->default(0.00)->after('shipping_amount');
                }
                if (!Schema::hasColumn('orders', 'total_amount')) {
                    $table->decimal('total_amount', 10, 2)->default(0.00)->after('tax_amount');
                }
                if (!Schema::hasColumn('orders', 'currency')) {
                    $table->string('currency')->default('USD')->after('total_amount');
                }
                if (!Schema::hasColumn('orders', 'payment_method')) {
                    $table->string('payment_method')->default('CREDIT_CARD')->after('currency');
                }
                if (!Schema::hasColumn('orders', 'payment_status')) {
                    $table->string('payment_status')->default('SUCCESS')->after('payment_method');
                }
                if (!Schema::hasColumn('orders', 'order_status')) {
                    $table->string('order_status')->default('PROCESSING')->after('payment_status');
                }
                if (!Schema::hasColumn('orders', 'transaction_id')) {
                    $table->string('transaction_id')->nullable()->after('order_status');
                }
                if (!Schema::hasColumn('orders', 'failure_reason')) {
                    $table->text('failure_reason')->nullable()->after('transaction_id');
                }
                if (!Schema::hasColumn('orders', 'notes')) {
                    $table->text('notes')->nullable()->after('failure_reason');
                }
            });
        }

        // 5. Ensure Order Items table
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('product_name');
                $table->string('part_number')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('quantity')->default(1);
                $table->decimal('total', 10, 2);
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
                $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            });
        }
    }

    public function down()
    {
    }
};
