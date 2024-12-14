CREATE DATABASE IF NOT EXISTS corrente_conta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE corrente_conta;

CREATE TABLE IF NOT EXISTS contas (
                                      id INT AUTO_INCREMENT PRIMARY KEY,
                                      numero VARCHAR(20) NOT NULL UNIQUE,
    saldo DECIMAL(10, 2) DEFAULT 0.00,
    limite_credito DECIMAL(10, 2) DEFAULT 1000.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE IF NOT EXISTS transacoes (
                                          id INT AUTO_INCREMENT PRIMARY KEY,
                                          conta_id INT NOT NULL,
                                          tipo ENUM('deposito', 'saque', 'transferencia') NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    descricao VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE CASCADE
    );

-- Banco de teste
CREATE DATABASE IF NOT EXISTS teste_corrente_conta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE teste_corrente_conta;

CREATE TABLE IF NOT EXISTS contas (
                                      id INT AUTO_INCREMENT PRIMARY KEY,
                                      numero VARCHAR(20) NOT NULL UNIQUE,
    saldo DECIMAL(10, 2) DEFAULT 0.00,
    limite_credito DECIMAL(10, 2) DEFAULT 1000.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE IF NOT EXISTS transacoes (
                                          id INT AUTO_INCREMENT PRIMARY KEY,
                                          conta_id INT NOT NULL,
                                          tipo ENUM('deposito', 'saque', 'transferencia') NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    descricao VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE CASCADE
    );
