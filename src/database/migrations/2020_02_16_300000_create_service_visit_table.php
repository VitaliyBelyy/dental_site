<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_visit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('visit_id')->unsigned()->index();
            $table->bigInteger('service_id')->unsigned()->index();
            $table->tinyInteger('tooth_id')->unsigned()->index()->nullable();
            $table->tinyInteger('service_count')->unsigned();
            $table->decimal('total_cost', 9, 2);
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
        Schema::dropIfExists('service_visit');
    }
}
