<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiFakeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_fake_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_id');
            $table->string('criteria_id');
            $table->string('name');
            $table->string('kpi');
            $table->string('kpi_standard');
//            $table->json('reality');
            $table->string('status');
            $table->date('created_time');
            $table->date('complete_time');
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
        Schema::dropIfExists('kpi_fake_tables');
    }
}
