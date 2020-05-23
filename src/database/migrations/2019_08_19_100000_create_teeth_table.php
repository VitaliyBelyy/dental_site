<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeethTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teeth', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('jaw');
            $table->tinyInteger('index');
            $table->string('icon_file_name');
            $table->boolean('reverse')->default(0);
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
        Schema::dropIfExists('teeth');
    }
}
