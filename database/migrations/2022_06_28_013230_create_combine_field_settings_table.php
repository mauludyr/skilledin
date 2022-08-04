<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCombineFieldSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combine_field_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_setting_id')
                ->nullable()
                ->constrained('profile_field_settings')
                ->nullOnDelete();

            $table->string('label_name')->nullable()->default(false);
            $table->boolean('is_public')->nullable()->default(false);
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
        Schema::dropIfExists('combine_field_settings');
    }
}
