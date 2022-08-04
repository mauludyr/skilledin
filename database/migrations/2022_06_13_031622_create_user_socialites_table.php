<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSocialitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_socialites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable()->default(null);
            $table->string('socialite_id')->nullable()->default(null);
            $table->string('socialite_firstname')->nullable()->default(null);
            $table->string('socialite_lastname')->nullable()->default(null);
            $table->string('socialite_email')->nullable()->default(null);
            $table->string('socialite_phone')->nullable()->default(null);
            $table->longText('socialite_image')->nullable()->default(null);
            $table->string('provider_name')->nullable()->default(null);
            $table->text('access_token')->nullable()->default(null);
            $table->string('expires_in')->nullable()->default(null);
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
        Schema::dropIfExists('user_socialites');
    }
}
