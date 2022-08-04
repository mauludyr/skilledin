<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogParticularChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_particular_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->string('field_name')->nullable()->default(null);
            $table->string('field_type')->nullable()->default(null);
            $table->string('old_value')->nullable()->default(null);
            $table->string('current_value')->nullable()->default(null);
            $table->string('attachment_name')->nullable()->default(null);
            $table->string('attachment_path')->nullable()->default(null);
            $table->enum('status', ['approve', 'reject']);
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
        Schema::dropIfExists('log_particular_changes');
    }
}
