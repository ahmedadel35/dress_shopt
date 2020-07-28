<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('instance')->nullable()->index();
            $table->foreignId('user_id')->constrained();
            // $table->foreignId('product_id')->constrained();
            // $table->float('price');
            // $table->decimal('save', 2, 0);
            // $table->integer('qty', false, true);
            // $table->tinyInteger('size');
            // $table->tinyInteger('color');
            // $table->float('total');
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
        Schema::dropIfExists('carts');
    }
}
