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
        Schema::dropIfExists('equations');

        Schema::create('equations', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("expression");
            $table->string("initials_expression");
            $table->timestamps();
        });

        Schema::create('equation_map', function (Blueprint $table) {
            $table->id();
            $table->foreignId("equation_id")->constrained('equations');
            $table->double("lower_limit")->nullable();
            $table->double("higher_limit")->nullable();
            $table->double("result");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equation_map');
        Schema::dropIfExists('equations');
    }
};
