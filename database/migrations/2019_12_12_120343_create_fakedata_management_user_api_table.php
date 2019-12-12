<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFakedataManagementUserApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('data');
            $table->timestamps();
        });

        Schema::create('user_criteria', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('data');
            $table->timestamps();
        });

        Schema::create('user_kpi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('data');
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
        Schema::dropIfExists('user_info');

        Schema::dropIfExists('user_criteria');

        Schema::dropIfExists('user_kpi');
    }
}
