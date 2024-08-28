
<?php
        /**
     *namespace Database\Migrations;
     */


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class  extends Migration
{
        /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('carteiras');
        Schema::create('carteiras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('student_id');
            $table->string('nomeAluno', 16);
            $table->string('matricula');
            $table->string('passCode');
            $table->date('data_nascimento');
            $table->string('nome_curso', 45);
            $table->date('dataInicioCurso')->nullable();
            $table->date('dataFimCurso')->nullable();
            $table->string('carteiraPdfUrl')->nullable();
            $table->date('expiredAt', 45)->nullable();
        });
 Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('carteiras');
    }
};
