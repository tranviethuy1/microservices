<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiUsersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('position');
            $table->integer('kpi');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone');
            $table->string('address');
            $table->string('birth');
            $table->string('department');
            $table->string('id_position');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('kpi_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_user')->unsigned();
            $table->string('complete_tasks');
            $table->integer('rate1');
            $table->string('working_hours');
            $table->integer('rate2');
            $table->string('time_late');
            $table->integer('rate3');
            $table->integer('month');
            $table->integer('year');
            $table->timestamps();
        });

        Schema::create('evaluate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('profits');
            $table->timestamps();
        });

        Schema::create('department', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('id_evaluate');
            $table->timestamps();
        });

        Schema::create('kpi_department', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_department')->unsigned();
            $table->integer('profits');
            $table->integer('month');
            $table->integer('year');
            $table->timestamps();
        });

        Schema::create('role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_employee');
            $table->integer('access');
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
        Schema::dropIfExists('kpis');

        Schema::dropIfExists('users');

        Schema::dropIfExists('password_resets');

        Schema::dropIfExists('kpi_users');

        Schema::dropIfExists('evaluate');

        Schema::dropIfExists('department');

        Schema::dropIfExists('kpi_department');

        Schema::dropIfExists('role');
    }
}
