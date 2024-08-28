
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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('email')->unique();
            $table->string('nome_completo', 255);
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')
                ->references('id')
                ->on('asaas_clients');
            $table->unsignedBigInteger('carteira_id');
            $table->foreign('carteira_id')
                ->references('id')
                ->on('carteiras')
                ->onDelete('cascade');
            $table->string('image_url', 45)->nullable();
            $table->string('cpf', 45)->nullable();
            $table->string('matricula', 45)->nullable();

        });
 Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
