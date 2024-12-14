<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id(); // ID auto incrementado
            $table->unsignedBigInteger('conta_id');
            $table->enum('tipo', ['deposito', 'saque', 'transferencia']);
            $table->decimal('valor', 10, 2);
            $table->string('descricao', 255)->nullable();
            $table->timestamps();

            // Chave estrangeira
            $table->foreign('conta_id')->references('id')->on('contas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacoes');
    }
}
