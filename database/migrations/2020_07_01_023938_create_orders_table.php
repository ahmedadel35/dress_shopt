<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('orderNum')->unique()->index()->default(bin2hex(random_bytes(5)));
            $table->string('transaction_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('address_id')->constrained('addresses');

            // $table->string('userMail')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed'])->default('processing');
            $table->decimal('total', 20, 6)->unsigned();
            $table->integer('qty', false, true)->unsigned();

            $table->boolean('paymentStatus')->default(false);
            $table->string('paymentMethod')->default('onDelivery');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
