<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('loanee_id');
            $table->unsignedBigInteger('reference')->unique();
            $table->integer('principal');
            $table->integer('rate_id');
            $table->integer('plan_id');
            $table->integer('duration');
            $table->string('status')->default('pending');
            $table->longText('comment')->nullable();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('loanee_id')->references('id')->on('loanees');
            $table->foreign('rate_id')->references('id')->on('rates');
            $table->foreign('plan_id')->references('id')->on('plans');
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
        Schema::dropIfExists('loans');
    }
}
