<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsoulImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carsoul_images', function (Blueprint $table) {
            $table->id();
            $table->string('img');
            $table->string('title')->nullable();
            $table->string('url');
            $table->index(['img', 'url', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carsoul_images');
    }
}
