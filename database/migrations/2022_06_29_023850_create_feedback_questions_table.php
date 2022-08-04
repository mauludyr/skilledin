<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_questions', function (Blueprint $table) {
            $table->id();
            $table->longText('description');
            $table->enum('type', ['general', 'hr']);
            $table->boolean('active_self')->default(true);
            $table->boolean('active_direct_manager')->default(true);
            $table->boolean('active_dotted_line_manager')->default(true);
            $table->boolean('active_peers')->default(true);
            $table->boolean('active_reverse_review')->default(true);
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
        Schema::dropIfExists('feedback_questions');
    }
}
