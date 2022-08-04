<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreignId('custom_field_id')
                ->nullable()
                ->constrained('custom_fields')
                ->nullOnDelete();

            $table->longText("value")->nullable()->default(null);

            $table->foreignId('field_setting_id')
                ->nullable()
                ->constrained('custom_field_settings')
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
        Schema::dropIfExists('user_custom_fields');
    }
}
