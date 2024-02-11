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
        Schema::create('excesses', function (Blueprint $table) {
            $table->id();
            $table->float('excess_balance', 8, 2)->nullable();
            $table->float('excess_balance_vat', 8, 2);
            $table->float('excess_charges', 8, 2)->nullable();
            $table->float('excess_charges_vat', 8, 2)->nullable();
            $table->float('non_vattable', 8, 2)->nullable();
            $table->float('total_bill', 8, 2);
            $table->float('deduction', 8, 2)->nullable();
            $table->unsignedBigInteger('series_id');
            $table->foreign('series_id')->references('id')->on('series')->onDelete('cascade');
            $table->unsignedBigInteger('assignee_id');
            $table->foreign('assignee_id')->references('id')->on('assignees')->onDelete('cascade');
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('excesses');
    }
};
