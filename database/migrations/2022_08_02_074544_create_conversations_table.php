<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->text('accomplishment')->nullable();
            $table->string('next_step')->nullable();
            $table->text('obstacle')->nullable();
            $table->date('step_date');
            $table->date('due_date');
            $table->boolean('is_ready')->default(true);
            $table->string('status')->nullable()->default(null);
            $table->timestamps();
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('conversations');
    }
}
