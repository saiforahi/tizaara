<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerifiedToCompanyBasicInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_basic_infos', function (Blueprint $table) {
            $table->tinyInteger('is_verified')->default(0)->comment('0=pending,1=verified,2=canceled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_basic_infos', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
}
