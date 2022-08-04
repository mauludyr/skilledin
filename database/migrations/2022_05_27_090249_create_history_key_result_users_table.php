<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryKeyResultUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('history_key_result_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('key_result_id')->nullable();
            $table->unsignedBigInteger('objective_id')->nullable();
            $table->bigInteger('progress_value_before')->nullable()->default(null);
            $table->bigInteger('progress_value_after')->nullable()->default(null);
            $table->unsignedBigInteger('task_status_id')->nullable();
            $table->longText('comment')->nullable()->default(null);
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
        Schema::dropIfExists('history_key_result_users');
    }
}
