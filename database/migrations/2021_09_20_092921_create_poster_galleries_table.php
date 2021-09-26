<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosterGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poster_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('fullname')->nullable();
            $table->string('username')->nullable();
            $table->string('poster_name')->nullable();
            $table->string('poster_url')->nullable();
            $table->bigInteger('qualification')->nullable();
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
        Schema::dropIfExists('poster_galleries');
    }
}
