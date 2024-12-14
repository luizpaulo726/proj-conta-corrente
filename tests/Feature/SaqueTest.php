<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Conta;

class SaqueTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_saque_com_saldo_suficiente()
    {
        $conta = Conta::factory()->create(['saldo' => 1000]);
        $response = $this->postJson("/api/contas/{$conta->numero}/sacar", [
            'valor' => 100
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Saque realizado com sucesso.']);

        $this->assertEquals(897, $conta->fresh()->saldo);
    }

    public function test_saque_com_saldo_insuficiente()
    {
        $conta = Conta::factory()->create(['saldo' => 50, 'limite_credito' => 0]);

        $response = $this->postJson("/api/contas/{$conta->numero}/sacar", [
            'valor' => 100
        ]);

        $response->assertStatus(422)
       ->assertJson(['error' => 'Saldo insuficiente.']);

        $this->assertEquals(50, $conta->fresh()->saldo);
    }

    public function test_conta_nao_encontrada()
    {
        $response = $this->postJson('/api/contas/999999999/sacar', [
            'valor' => 100
        ]);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Conta não encontrada.']);
    }

    public function test_validacao_valor_invalido()
    {
        $conta = Conta::factory()->create(['saldo' => 1000]);

        $response = $this->postJson("/api/contas/{$conta->numero}/sacar", [
            'valor' => 'vccvvccv' // valor inválido
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['valor']);
    }

    public function test_limite_credito_suficiente()
    {
        $conta = Conta::factory()->create(['saldo' => 0, 'limite_credito' => 100]);

        $response = $this->postJson("/api/contas/{$conta->numero}/sacar", [
            'valor' => 50
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Saque realizado com sucesso.']);

        $this->assertEquals(-51.50, $conta->fresh()->saldo);
    }

}
