
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
        Schema::dropIfExists('asaas_clients');
        Schema::create('asaas_clients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('asaas_cobranca_id')->nullable();
            $table->foreign('asaas_cobranca_id')
                ->references('id')
                ->on('asaas_cobrancas')
                ->onDelete('cascade');
            $table->string('costumer_id', 50);
            $table->string('name');
            $table->string('cpfCnpj', 11);
            $table->string('email');
            $table->unsignedBigInteger("student_id")->unique();
            $table->unsignedBigInteger("service_id");
            $table->foreign('service_id')
                ->references('id')->on('servicos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
 Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('asaas_clients');
    }
};
