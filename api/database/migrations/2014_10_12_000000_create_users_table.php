<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->boolean('account_type')->default(1)->comment('1=Supplier, 2=Buyer, 0=Both');
            $table->bigInteger('parent_id')->default(0);
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('photo_type')->default(0)->comment('0=upload, 1=Google-FB');
            $table->string('photo')->default('upload/photo/user.png');
            $table->string('job_title')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique()->nullable();
            $table->string('telephone')->nullable();
            $table->string('password')->nullable();
            $table->string('verificationToken')->nullable();
            $table->bigInteger('facebook_id')->nullable();
            $table->string('is_verified')->default(0)->comment('1=Verify');
            $table->string('status')->default(0);
            $table->string('registration_type')->default(0)->comment('1=Data Submit,');
            $table->string('ip_address')->nullable();
            $table->integer('customer_package_id')->nullable();
            $table->integer('remaining_uploads')->nullable();
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
        Schema::dropIfExists('users');
    }
}
