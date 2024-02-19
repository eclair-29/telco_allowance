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
        Schema::create('assignees', function (Blueprint $table) {
            $table->id();
            $table->string('assignee')->nullable();
            $table->string('assignee_code')->nullable()->unique(); // EMPLOYEE ID
            $table->string('account_no')->unique();
            $table->string('phone_no')->unique();
            $table->float('allowance', 8, 2);
            $table->unsignedBigInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->string('notes')->nullable();
            $table->boolean('SIM_only')->nullable();
            $table->string('data_allocation')->nullable();
            $table->date('contract_validity_date')->nullable();
            $table->integer('contract_days_left')->nullable();
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
        Schema::dropIfExists('assignees');
    }
};
