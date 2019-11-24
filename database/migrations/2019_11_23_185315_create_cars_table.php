<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('brand_id')->unsigned()->index();
            $table->bigInteger('model_id')->unsigned()->index();
            $table->string('sku', 50)->unique();
            $table->string('bodyType', 100)->nullable();
            $table->string('name', 100)->index();
            $table->text('description')->nullable();
            $table->string('mileage', 100)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->date('price_valid_until')->nullable();
            $table->string('availability', 20)->nullable()->index();
            $table->string('subtitle', 100)->nullable();
            $table->string('year_of_manufacture', 20)->nullable();
            $table->string('exchange_type', 30)->nullable()->index();
            $table->text('accessories')->nullable();
            $table->text('image')->nullable();
            $table->text('url')->nullable();

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('model_id')->references('id')->on('models');

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
        Schema::dropIfExists('cars');
    }
}
