-- =====================================================
-- Banco de dados: monitoring_system
-- Sistema de Monitoramento de Servidores (PHP + MySQL)
-- =====================================================

CREATE DATABASE IF NOT EXISTS monitoring_system
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE monitoring_system;

-- =====================================================
-- Tabela: servers
-- Cadastro dos servidores monitorados
-- =====================================================
CREATE TABLE servers (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hostname            VARCHAR(255)    NOT NULL,
    ip_address          VARCHAR(45)     NOT NULL, -- suporta IPv4 e IPv6
    description         TEXT,
    location            VARCHAR(100),             -- datacenter / sala / rack
    os_name             VARCHAR(50),
    os_version          VARCHAR(50),
    cpu_cores           SMALLINT UNSIGNED,
    total_memory_mb     BIGINT UNSIGNED,
    total_disk_gb       BIGINT UNSIGNED,
    environment         ENUM('production','staging','development') NOT NULL DEFAULT 'production',
    status              ENUM('active','inactive','maintenance','decommissioned') NOT NULL DEFAULT 'active',
    last_heartbeat_at   DATETIME NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_servers_hostname (hostname),
    UNIQUE KEY uq_servers_ip_address (ip_address),
    INDEX idx_servers_status (status),
    INDEX idx_servers_environment (environment)
) ENGINE=InnoDB;

-- =====================================================
-- Tabela: metric_types
-- Catálogo dos tipos de métricas coletadas
-- =====================================================
CREATE TABLE metric_types (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50)  NOT NULL,
    unit        VARCHAR(20),
    description VARCHAR(255),
    UNIQUE KEY uq_metric_types_name (name)
) ENGINE=InnoDB;

-- =====================================================
-- Tabela: server_metrics
-- Série temporal das métricas coletadas de cada servidor
-- =====================================================
CREATE TABLE server_metrics (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_id       BIGINT UNSIGNED NOT NULL,
    metric_type_id  INT UNSIGNED    NOT NULL,
    value           DECIMAL(12,2)   NOT NULL,
    collected_at    DATETIME        NOT NULL,
    CONSTRAINT fk_metrics_server      FOREIGN KEY (server_id)      REFERENCES servers(id)      ON DELETE CASCADE,
    CONSTRAINT fk_metrics_metric_type FOREIGN KEY (metric_type_id) REFERENCES metric_types(id) ON DELETE RESTRICT,
    INDEX idx_metrics_server_type_time (server_id, metric_type_id, collected_at)
) ENGINE=InnoDB;

-- =====================================================
-- Tabela: services
-- Serviços/processos monitorados em cada servidor
-- =====================================================
CREATE TABLE services (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_id       BIGINT UNSIGNED NOT NULL,
    name            VARCHAR(100)    NOT NULL,
    port            SMALLINT UNSIGNED,
    status          ENUM('running','stopped','unknown') NOT NULL DEFAULT 'unknown',
    last_checked_at DATETIME NULL,
    CONSTRAINT fk_services_server FOREIGN KEY (server_id) REFERENCES servers(id) ON DELETE CASCADE,
    INDEX idx_services_server (server_id)
) ENGINE=InnoDB;

-- =====================================================
-- Tabela: alert_rules
-- Regras de alerta por servidor/tipo de métrica
-- =====================================================
CREATE TABLE alert_rules (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    server_id       BIGINT UNSIGNED NULL, -- NULL = regra global, aplicada a todos os servidores
    metric_type_id  INT UNSIGNED    NOT NULL,
    operator        ENUM('>','>=','<','<=','=') NOT NULL,
    threshold_value DECIMAL(12,2)   NOT NULL,
    severity        ENUM('info','warning','critical') NOT NULL DEFAULT 'warning',
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    CONSTRAINT fk_rules_server      FOREIGN KEY (server_id)      REFERENCES servers(id)      ON DELETE CASCADE,
    CONSTRAINT fk_rules_metric_type FOREIGN KEY (metric_type_id) REFERENCES metric_types(id) ON DELETE RESTRICT,
    INDEX idx_rules_server (server_id),
    INDEX idx_rules_metric_type (metric_type_id)
) ENGINE=InnoDB;

-- =====================================================
-- Tabela: alerts
-- Histórico de disparos das regras de alerta
-- =====================================================
CREATE TABLE alerts (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    alert_rule_id  INT UNSIGNED    NOT NULL,
    server_id      BIGINT UNSIGNED NOT NULL,
    triggered_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    resolved_at    DATETIME NULL,
    status         ENUM('open','acknowledged','resolved') NOT NULL DEFAULT 'open',
    message        TEXT,
    CONSTRAINT fk_alerts_rule   FOREIGN KEY (alert_rule_id) REFERENCES alert_rules(id) ON DELETE CASCADE,
    CONSTRAINT fk_alerts_server FOREIGN KEY (server_id)     REFERENCES servers(id)      ON DELETE CASCADE,
    INDEX idx_alerts_status (status),
    INDEX idx_alerts_server (server_id)
) ENGINE=InnoDB;

