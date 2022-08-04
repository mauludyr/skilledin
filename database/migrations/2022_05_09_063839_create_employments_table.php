<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreignId('grade_id')->nullable()
                ->constrained('grades')
                ->nullOnDelete();

            $table->foreignId('job_position_id')->nullable()
                ->constrained('job_positions')
                ->nullOnDelete();

            $table->foreignId('employment_type_id')->nullable()
                ->constrained('employment_types')
                ->nullOnDelete();


            $table->foreignId('salary_id')->nullable()
                ->constrained('locations')
                ->nullOnDelete();

            $table->double('salary')->default(null);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employments');
    }
}
