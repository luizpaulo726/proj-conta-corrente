# Projeto Conta Corrente

## Passo a Passo para Rodar o Projeto

### 1. Clonar o Projeto
Para começar, clone o repositório do projeto para sua máquina local:

```bash
git clone <URL_DO_REPOSITORIO>
```

### 2. Entrar no Diretório do Projeto
Entre no diretório do projeto:

```bash
cd <NOME_DO_DIRETORIO>
```

### 3. Iniciar o Docker Compose
Para rodar o ambiente de containers do Docker, execute o seguinte comando:

```bash
docker-compose up -d
```

Este comando vai iniciar os containers necessários para rodar o projeto.

### 4. Verificar se os Containers Estão Rodando
Após o Docker Compose iniciar os containers, verifique se tudo está funcionando corretamente com o comando:

```bash
docker ps
```

Isso irá listar os containers em execução. Localize o container com o nome `app-laravel` e copie o ID correspondente.

### 5. Acessar o Container
Agora, acesse o container do `app-laravel` com o comando:

```bash
docker exec -it <ID_COPIADO> bash
```

Isso abrirá um terminal dentro do container.

### 6. Instalar as Dependências
Dentro do container, execute o comando para instalar as dependências do projeto:

```bash
composer install
```

### 7. Configurar o Ambiente
Copie o arquivo de exemplo `.env` para a configuração padrão do ambiente:

```bash
cp .env.example .env
```

### 8. Gerar a Chave de Aplicação
Agora, gere a chave de aplicação para o Laravel:

```bash
php artisan key:generate
```

### 9. Acessar o Projeto
Após os passos acima, o ambiente está configurado. Agora, abra o navegador e acesse a seguinte URL para testar a API:

```
http://localhost:8080/api/contas
```

### 10. Testar a Aplicação

Adicionar os passos para o `.env.testing`:

```bash
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=teste_corrente_conta
DB_USERNAME=root
DB_PASSWORD=Blue@2021
```

(trocar pelos dados corretos que você gerou)

```bash
APP_KEY=base64:HUHQFXhWDHsJDh6M+CFE7ChA7DqBv74KFWssXKtQ6AA=
```

Para rodar os testes, no terminal do Docker, execute:

```bash
php artisan test
```

### JSON Exemplo - Testado no Postman

**Criar conta:**

```
http://localhost:8080/api/contas
{
    "numero": "0123456", 
    "saldo": 0,
    "limite_credito": 500.00
}
```

**Depositar em conta:**

```
http://localhost:8080/api/contas/123456789/depositar
{
    "valor": 70
}
```

**Sacar da conta:**

```
http://localhost:8080/api/contas/123456789/sacar
{
    "valor": 70
}
```

**Transferir entre contas:**

```
POST /api/transacoes/transferir

{
    "numeroContaOrigem": "12345",
    "numeroContaDestino": "67890",
    "valor": 100.00,
    "descricao": "Pagamento de serviço"
}
```

**Processar lote de transações:**

```
http://localhost:8080/api/contas/processar-lote
{
    "transacoes": [
        {
            "tipo": "deposito",
            "numero_origem": "123456789",
            "valor": 20,
            "descricao": "Depósito em conta 123456789"
        },
        {
            "tipo": "saque",
            "numero_origem": "123456789",
            "valor": 10,
            "descricao": "Saque da conta 123456789"
        },
        {
            "tipo": "transferencia",
            "numeroContaOrigem": "123456789",
            "numeroContaDestino": "123456",
            "valor": 100,
            "descricao": "Transferência para conta 123456"
        },
        {
            "tipo": "transferencia",
            "numeroContaOrigem": "088682",
            "numeroContaDestino": "123456",
            "valor": 100,
            "descricao": "Transferência para conta 123456"
        }
    ]
}
```

---
