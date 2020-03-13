<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('medical_info')->nullable();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('anamnesis_id')->unsigned()->index()->nullable();
            $table->string('original_file_name')->nullable();
            $table->string('hash_file_name')->nullable();
            $table->decimal('total_accrued', 9, 2)->default(0);
            $table->decimal('total_paid', 9, 2)->default(0);
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
        Schema::dropIfExists('patients');
    }
}
