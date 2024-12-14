<?php

namespace App\Console\Commands;

use App\Models\Conta;
use App\Services\TransacaoService;
use Illuminate\Console\Command;

class ProcessarLoteTransacoes extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lote:processar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa uma lista de transações em lote';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transacoes = [
            // Lista de transações de exemplo
            ['conta_id' => 1, 'tipo' => 'deposito', 'valor' => 500],
            ['conta_id' => 1, 'tipo' => 'saque', 'valor' => 300],
        ];

        foreach ($transacoes as $transacao) {
            try {
                $conta = Conta::findOrFail($transacao['conta_id']);
                app(TransacaoService::class)->processarTransacao(
                    $conta,
                    $transacao['tipo'],
                    $transacao['valor']
                );
            } catch (\Exception $e) {
                $this->error("Erro ao processar transação: " . $e->getMessage());
            }
        }

        $this->info('Transações processadas com sucesso.');
    }
}
