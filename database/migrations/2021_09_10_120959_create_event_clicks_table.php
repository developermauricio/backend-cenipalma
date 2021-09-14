<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('fullname')->nullable();
            $table->string('username')->nullable();
            $table->string('name_scene')->nullable();
            $table->string('click_name')->nullable();
            $table->timestamp('date_visit')->nullable();
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
        Schema::dropIfExists('event_clicks');
    }
}
