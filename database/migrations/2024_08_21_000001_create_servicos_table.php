
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
        Schema::dropIfExists('servicos');
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->longText('url');
            $table->longText('method')->nullable();
            $table->string('service_type', 32);
            $table->integer('service_id')->nullable();
            $table->string('payload', 45)->nullable();
            $table->string('response', 45)->nullable();
            $table->tinyInteger('exec')->nullable();
            $table->tinyInteger('run_by_administrator')->nullable();
        });
 Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('servicos');
    }
};
