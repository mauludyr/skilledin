<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->string('first_name');
            $table->string('last_name')->nullable()->default(null);
            $table->string('middle_name')->nullable()->default(null);
            $table->date('birthday')->nullable()->default(null);
            $table->string('pronouns')->nullable()->default(null);
            $table->string('superpower')->nullable()->default(null);
            $table->longText('address')->nullable()->default(null);
            $table->string('phone_number')->nullable()->default(null);
            $table->string('personal_email')->nullable()->default(null);
            $table->string('emergency_contact_name')->nullable()->default(null);
            $table->string('emergency_contact_number')->nullable()->default(null);
            $table->date('date_joined')->nullable()->default(null);

            $table->text('image_filename')->nullable()->default(null);
            $table->text('image_filepath')->nullable()->default(null);

            $table->foreignId('nationality_id')->nullable()
                ->constrained('nationalities')
                ->nullOnDelete();

            $table->foreignId('location_id')->nullable()
                ->constrained('locations')
                ->nullOnDelete();

            $table->string('location_name')->nullable()->default(null);
            $table->boolean('is_public')->nullable()->default(true);
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
        Schema::dropIfExists('profiles');
    }
}
