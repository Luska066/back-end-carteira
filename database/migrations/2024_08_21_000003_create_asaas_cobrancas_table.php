
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
        Schema::dropIfExists('asaas_cobrancas');
        Schema::create('asaas_cobrancas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('asaas_client_id');
            $table->string('customer', 50);
            $table->string('billingType');
            $table->float('value');
            $table->date('dueDate');
            $table->string('paymentLink', 45)->nullable();
            $table->string('status', 45);
            $table->string('invoiceUrl', 45);
            $table->date('paymentDate')->nullable();
            $table->unsignedBigInteger('service_id');
            $table->index(["service_id"], 'fk_asaas_cobrancas_servicos_idx');
            $table->foreign('service_id')
                ->references('id')->on('servicos')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
 Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('asaas_cobrancas');
    }
};
