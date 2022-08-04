<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDurationTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duration_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_time_id')
                ->nullable()
                ->constrained('period_times')
                ->onDelete('set null');

            $table->foreignId('review_date_id')
                ->nullable()
                ->constrained('reviews_dates')
                ->onDelete('set null');

            //Duration Time
            $table->date("duration_start")->nullable()->default(null);
            $table->date("duration_end")->nullable()->default(null);

            //Performance
            $table->date("performance_start")->nullable()->default(null);
            $table->date("performance_end")->nullable()->default(null);
            $table->string('reporting_year')->nullable()->default(null);
            $table->boolean("is_include")->nullable()->default(false);
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
        Schema::dropIfExists('duration_times');
    }
}
