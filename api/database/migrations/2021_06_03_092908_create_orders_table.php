<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('email',100)->nullable();
            $table->string('phone',100);
            $table->text('address');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->json('product')->nullable();
            $table->string('product_variant',255)->nullable();
            $table->json('offer')->nullable();
            $table->text('billing_info')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedInteger('zip_code')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('unit_id')->nullable();
            $table->double('unit_price')->default(0);
            $table->double('offer_price')->default(0);
            $table->double('sub_total')->default(0);
            $table->double('shipping_charge')->default(0);
            $table->double('vat')->default(0);
            $table->double('total_amount')->default(0);
            $table->double('pay_amount')->default(0);
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->tinyInteger('payment_type')->default(1);// 1 for cash on delivery, 2 for ssl commerze
            $table->json('payment_info')->nullable();
            $table->string('status',100);//payment status
            $table->tinyInteger('order_status')->default(0);//0 =pending, 1=approved, 2=cancel, 3=shipping, 4=delivered
            $table->tinyInteger('approval_status')->default(0);//admin approval status 0 =pending, 1=approved, 2=cancel
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->text('browser')->nullable();
            $table->string('transaction_id',100);
            $table->string('currency',20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
