<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews_dates', function (Blueprint $table) {
            $table->id();
            $table->string('month')->nullable()->default(null);
            $table->bigInteger('year')->nullable()->default(null);
            $table->foreignId('frequency_id')
                ->nullable()
                ->constrained('frequency_periods')
                ->onDelete('set null');
            $table->boolean("is_include_review")->nullable()->default(false);
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
        Schema::dropIfExists('reviews_dates');
    }
}
