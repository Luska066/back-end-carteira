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
        Schema::table('asaas_cobrancas', function (Blueprint $table) {
            $table->renameColumn('customer','id_charge');
        });
        Schema::table('carteiras', function (Blueprint $table) {
            $table->unsignedBigInteger('asaas_cobranca_id')->change();
            $table->foreign('asaas_cobranca_id')
                ->references('id')
                ->on('asaas_cobrancas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
