<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('key_result_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('label_id');
            $table->unsignedBigInteger('delegate_id');
            $table->string('task_name');
            $table->text('task_note');
            $table->string('duration')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_starred')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('tasks');
    }
}
