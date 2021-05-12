<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('pro_id')->nullable();
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->timestamps();
            $table->primary(['pro_id','tag_id']);

            $table->foreign('pro_id')
            ->references(['id'])->on('pros')
            ->onDelete('cascade');

            $table->foreign('tag_id')
            ->references(['id'])->on('tags')
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
        Schema::dropIfExists('pro_tag');
    }
}
