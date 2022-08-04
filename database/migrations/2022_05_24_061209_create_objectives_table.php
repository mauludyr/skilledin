<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objectives', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->unsignedBigInteger('duration_period_id')->nullable()->default(null);
            $table->date('due_date')->nullable()->default(null);

            $table->foreignId('owner_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreignId('objective_level_id')
                ->constrained('objective_levels')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->unsignedBigInteger('parent_object_id')->nullable()->default(null);
            $table->string('objective_status')->nullable();
            $table->boolean('is_new')->nullable()->default(null);
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
        Schema::dropIfExists('objectives');
    }
}
