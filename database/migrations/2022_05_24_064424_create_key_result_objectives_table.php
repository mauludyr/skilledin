<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyResultObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_result_objectives', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable()->default(null);
            $table->foreignId('objective_id')
                ->constrained('objectives')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreignId('measure_id')
                ->nullable()
                ->constrained('measures')
                ->onDelete('set null');

            $table->string('start_value')->nullable()->default(0);
            $table->string('target')->nullable()->default(null);
            $table->string('unit')->nullable()->default(null);
            $table->date('due_date')->nullable()->default(null);
            $table->boolean('is_draft')->nullable()->default(false);

            $table->foreignId('owner_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->bigInteger("old_progress_value")->nullable()->default(null);
            $table->bigInteger("last_progress_value")->nullable()->default(null);
            $table->foreignId('last_status_id')
                ->nullable()
                ->constrained('task_statuses')
                ->onDelete('set null');


            $table->longText("last_comment")->nullable()->default(null);

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
        Schema::dropIfExists('key_result_objectives');
    }
}
