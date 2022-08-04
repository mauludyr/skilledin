<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_teams', function (Blueprint $table) {
            $table->id();
            $table->string("team_name")->nullable()->default(null);
            $table->foreignId('manager_team_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->unsignedBigInteger('parent_team_id')->nullable()->default(null);
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
        Schema::dropIfExists('organization_teams');
    }
}
