<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('steps', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('redirect_uri');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('registration_steps', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('id_step');
            $table->unsignedBigInteger('id_student');
            $table->foreign('id_step')
                ->references('id')
                ->on('steps')
                ->onDelete('cascade');
            $table->foreign('id_student')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('registration_steps');
        Schema::dropIfExists('steps');
    }
};
