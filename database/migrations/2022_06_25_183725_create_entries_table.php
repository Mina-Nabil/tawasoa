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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string("company_name");
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });

        Schema::create('entry_variable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('entries');
            $table->foreignId('variable_id')->constrained('variables');
            $table->double('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entry_variable');
        Schema::dropIfExists('entries');
    }
};
