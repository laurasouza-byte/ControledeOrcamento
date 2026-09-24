# ControledeOrcamento

Gerenciamento de orçamento mensal web.

## Tipos e categorias

- Tipos: `receita`, `despesa`
- Categorias: Alimentação, Transporte, Moradia, Lazer, Saúde, Salário, Outros

## Banco
CREATE DATABASE IF NOT EXISTS `controle_orcamento` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `controle_orcamento`;

CREATE TABLE IF NOT EXISTS `lancamentos` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `valor` DECIMAL(10, 2) NOT NULL,
    `tipo` ENUM('receita', 'despesa') NOT NULL,
    `categoria` VARCHAR(30) NOT NULL,
    `data` DATE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
