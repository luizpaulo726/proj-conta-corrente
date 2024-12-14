
# Passo a Passo para Rodar o Projeto Conta Corrente

### 1. Clonar o Repositório do Projeto
Comece clonando o repositório para a sua máquina local com o comando abaixo:

```bash
git clone <URL_DO_REPOSITORIO>
```
### 2. Entrar no Diretório do Projeto
Acesse o diretório do projeto que você acabou de clonar:

```bash
cd <NOME_DO_DIRETORIO>
```
### 3. Iniciar o Docker Compose
Execute o comando a seguir para iniciar os containers necessários:

```bash
docker-compose up -d
```
Esse comando iniciará todos os containers que o projeto requer.
### 4. Verificar se os Containers Estão Rodando
Para garantir que os containers estão funcionando, execute:

```bash
docker ps
```
Procure pelo container `app-laravel` e copie o seu ID.
### 5. Acessar o Container do Laravel
Com o ID do container em mãos, acesse o container do Laravel com:

```bash
docker exec -it <ID_COPIADO> bash
```
Isso abrirá um terminal dentro do container.
### 6. Instalar Dependências do Projeto
Dentro do container, instale todas as dependências do projeto:

```bash
composer install
```
### 7. Configurar o Ambiente
Copie os arquivos de configuração para o ambiente padrão:

```bash
cp .env.example .env
cp .env.example .env.testing
```
### 8. Gerar a Chave de Aplicação
Em seguida, gere a chave de aplicação do Laravel:

```bash
php artisan key:generate
```
### 9. Acessar o Projeto
Com o ambiente configurado, acesse a aplicação no navegador através da URL:

```
http://localhost:8080
```
### 10. Configurar o Ambiente no `.env.testing`
Adicione os dados de configuração no arquivo `.env.testing`:

```bash
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=teste_corrente_conta
DB_USERNAME=root
DB_PASSWORD=Blue@2021
APP_KEY=base64:HUHQFXhWDHsJDh6M+CFE7ChA7DqBv74KFWssXKtQ6AA= (substitua pela chave gerada)
```
### 10.1 Configurar o Ambiente no `.env`
Adicione os dados de configuração no arquivo `.env`:

```bash
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=corrente_conta
DB_USERNAME=root
DB_PASSWORD=Blue@2021

### Comandos de Teste
Para rodar os testes no terminal dentro do container:

```bash
docker exec -it app-laravel bash
php artisan test
```

### Exemplo de Requisições JSON no Postman
**Criar uma conta corrente:**

```
POST /api/contas
{
    "numero": "0123456",
    "saldo": 0,
    "limite_credito": 500.00
}
```

**Depositar em uma conta:**

```
POST /api/contas/123456789/depositar
{
    "valor": 70
}
```

**Sacar da conta:**

```
POST /api/contas/123456789/sacar
{
    "valor": 70
}
```

**Transferência entre contas:**

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
POST /api/contas/processar-lote
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
