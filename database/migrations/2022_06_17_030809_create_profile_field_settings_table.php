<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileFieldSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_field_settings', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->nullable();
            $table->string('field_slug')->nullable()->default(null);
            $table->foreignId('profile_setting_id')
                ->nullable()
                ->constrained('profile_settings')
                ->nullOnDelete();
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
        Schema::dropIfExists('profile_field_settings');
    }
}
