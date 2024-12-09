<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('title');
            $table->text('description');
            $table->text('image');
            $table->string('size')->nullable();
            $table->string('colors')->nullable();
            $table->string('price');
            $table->unsignedBigInteger('category_id'); 
            $table->enum('isValid', ['yes', 'no'])->default('yes');
            $table->integer('discount');
            $table->integer('stars');
            $table->timestamps();
    
            // Add foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
};
