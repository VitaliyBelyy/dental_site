<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToServiceVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_visit', function (Blueprint $table) {
            $table->foreign('visit_id')
                ->references('id')
                ->on('visits')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_visit', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->dropForeign(['service_id']);
        });
    }
}
