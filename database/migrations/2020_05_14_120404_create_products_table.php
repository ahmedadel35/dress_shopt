<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('cart_id')->nullable()->index();
            $table->string('category_slug')->nullable();
            $table->string('title');
            $table->string('slug')->nullable()->unique()->index();
            $table->float('price');
            $table->float('save')->default(0);
            $table->integer('qty');
            $table->string('colors');
            $table->string('sizes')->nullable();
            $table->longText('images');
            $table->text('info')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

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
        Schema::dropIfExists('products');
    }
}
