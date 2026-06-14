# Question C - Server Monitoring Process

## Overview

To monitor the infrastructure and provide data to the web application, I propose an agent-based monitoring architecture. A monitoring agent periodically collects information from the servers, stores the results in the database, and the web application consumes this data to display dashboards, metrics, alerts, and server status information.

The monitoring process is executed on a schedule (e.g., every 5 minutes using cron) and consists of the following steps:

1. Check if each server is up and running
2. Retrieve the list of monitored servers
3. Verify if the web server process is running
4. Collect CPU and memory usage
5. Store collected information in the database
6. Evaluate alert rules and generate alerts when necessary

---

## Proposed Architecture

```text
+---------------------+
| Monitored Servers   |
+---------------------+
          ^
          |
          | SSH
          |
+---------------------+
| Monitoring Agent    |
| PHP CLI + Cron      |
+---------------------+
          |
          | INSERT / UPDATE
          v
+---------------------+
| MySQL Database      |
+---------------------+
          |
          | REST API
          v
+---------------------+
| Web Dashboard       |
+---------------------+
```

### Architecture Rationale

This architecture separates monitoring responsibilities from presentation responsibilities.

Benefits:

* Scalability: new servers can be added through the database without changing the monitoring code.
* Decoupling: monitoring continues even if the web application is temporarily unavailable.
* Historical analysis: metrics are stored as time-series data and can be visualized later.
* Extensibility: new metrics can be added without modifying the database schema.
* Simplicity: SSH is already available in most Linux environments and does not require installing additional monitoring software on target servers.

---

# Remote Command Execution

To collect information from remote servers, the monitoring agent uses SSH connections.

A dedicated monitoring user (e.g., `monitor`) is configured on every monitored server with SSH key-based authentication. This allows the monitoring agent to securely execute commands without storing passwords or requiring manual intervention.

Architecture:

```text
+----------------------+
| Monitoring Agent     |
| (PHP CLI Script)     |
+----------------------+
           |
           | SSH
           v
+----------------------+
| Linux Server         |
+----------------------+
           |
           +--> ping
           +--> systemctl is-active nginx
           +--> free -m
           +--> top -bn1
```

The monitoring process retrieves the server IP address from the database and executes remote commands through SSH.

Example:

```bash
ssh monitor@10.0.0.10 "free -m"
```

CPU usage:

```bash
ssh monitor@10.0.0.10 "top -bn1"
```

Web server status:

```bash
ssh monitor@10.0.0.10 "systemctl is-active nginx"
```

Example PHP implementation:

```php
$command = sprintf(
    'ssh monitor@%s "free -m"',
    $serverIp
);

$output = shell_exec($command);
```

This approach was chosen because it is simple, secure, and commonly used in Linux environments. It does not require additional software installation on monitored servers and can easily scale by adding new server records to the database.

---

# Step 1 - Check if Each Server is Up and Running

The monitoring agent retrieves all active servers from the database.

```sql
SELECT id, hostname, ip_address
FROM servers
WHERE status = 'active';
```

For each server, a connectivity test is executed.

Example Linux command:

```bash
ping -c 3 10.0.0.10
```

Example PHP code:

```php
$output = shell_exec(
    "ping -c 3 {$ipAddress}"
);
```

### Success Scenario

If the server responds:

```text
Server Status = UP
```

The heartbeat timestamp is updated:

```sql
UPDATE servers
SET last_heartbeat_at = NOW()
WHERE id = ?;
```

### Failure Scenario

If the server does not respond:

```text
Server Status = DOWN
```

An alert can be generated to notify administrators.

Example:

```sql
INSERT INTO alerts (...)
```

The alert will appear in the monitoring dashboard.

---

# Step 2 - Retrieve the List of Servers to Monitor

The monitoring process retrieves servers from the `servers` table.

Example:

```sql
SELECT *
FROM servers
WHERE status = 'active';
```

Only active production servers may be monitored.

Example:

```sql
SELECT *
FROM servers
WHERE
    status = 'active'
    AND environment = 'production';
```

This approach allows administrators to control monitoring behavior directly from the database.

The monitoring agent executes this query at the beginning of every monitoring cycle.

---

# Step 3 - Verify if the Web Server Process is Running

Each server may have one or more monitored services registered in the `services` table.

Example:

| Server | Service |
| ------ | ------- |
| web01  | nginx   |
| web01  | php-fpm |
| api01  | apache2 |

The monitoring agent verifies whether each service is running.

Example Linux command:

```bash
systemctl is-active nginx
```

Alternative:

```bash
ps aux | grep nginx
```

Executed remotely via SSH:

```bash
ssh monitor@10.0.0.10 "systemctl is-active nginx"
```

Example PHP:

```php
$status = trim(
    shell_exec(
        "ssh monitor@{$serverIp} 'systemctl is-active nginx'"
    )
);
```

Possible results:

```text
active
inactive
failed
```

The result is stored in the database:

```sql
UPDATE services
SET
    status = 'running',
    last_checked_at = NOW()
WHERE id = ?;
```

If a critical service is stopped, an alert should be created.

Example alert:

```text
Critical: nginx process stopped on web01
```

---

# Step 4 - Collect CPU and Memory Usage

The monitoring agent collects performance information remotely through SSH.

## CPU Usage

Example command:

```bash
ssh monitor@10.0.0.10 "top -bn1"
```

Alternative:

```bash
ssh monitor@10.0.0.10 "mpstat"
```

## Memory Usage

Example command:

```bash
ssh monitor@10.0.0.10 "free -m"
```

Example result:

```text
CPU Usage: 64.5%
Memory Usage: 72.0%
```

The values are stored in the `server_metrics` table.

CPU:

```sql
INSERT INTO server_metrics
(
    server_id,
    metric_type_id,
    value,
    collected_at
)
VALUES
(
    1,
    1,
    64.5,
    NOW()
);
```

Memory:

```sql
INSERT INTO server_metrics
(
    server_id,
    metric_type_id,
    value,
    collected_at
)
VALUES
(
    1,
    2,
    72.0,
    NOW()
);
```

Where:

| Metric Type | Description  |
| ----------- | ------------ |
| 1           | CPU Usage    |
| 2           | Memory Usage |

This structure allows storing historical measurements for chart generation and trend analysis.

---

# Alert Evaluation

After metrics are collected, the monitoring agent evaluates the configured alert rules.

Example rule:

```text
CPU Usage > 80%
Severity = Critical
```

Stored in:

```sql
alert_rules
```

Query:

```sql
SELECT *
FROM alert_rules
WHERE
    metric_type_id = 1
    AND is_active = TRUE;
```

If the collected metric exceeds the threshold:

```text
CPU Usage = 85%
Threshold = 80%
```

A new alert is created:

```sql
INSERT INTO alerts
(
    alert_rule_id,
    server_id,
    message
)
VALUES
(
    5,
    1,
    'CPU usage exceeded threshold'
);
```

The alert will then be displayed in the web dashboard.

---

# Database Usage

The monitoring process interacts with the following tables:

| Table          | Purpose                                    |
| -------------- | ------------------------------------------ |
| servers        | Stores server information and heartbeat    |
| services       | Stores monitored services and their status |
| metric_types   | Defines available metric categories        |
| server_metrics | Stores collected metrics over time         |
| alert_rules    | Defines alert thresholds                   |
| alerts         | Stores generated alerts                    |

This structure provides complete traceability of monitoring information.

---

# Additional Information Worth Monitoring

Besides availability, CPU, and memory usage, I would also monitor:

## Disk Usage

Command:

```bash
ssh monitor@10.0.0.10 "df -h"
```

Reason:

High disk utilization is one of the most common causes of production incidents.

---

## Disk I/O

Command:

```bash
ssh monitor@10.0.0.10 "iostat"
```

Reason:

Helps identify storage bottlenecks.

---

## Network Traffic

Command:

```bash
ssh monitor@10.0.0.10 "sar -n DEV"
```

Reason:

Detects abnormal network activity and bandwidth saturation.

---

## System Load Average

Command:

```bash
ssh monitor@10.0.0.10 "uptime"
```

Reason:

Provides an overall view of server load.

---

## SSL Certificate Expiration

Reason:

Prevents service outages caused by expired certificates.

---

## Application Response Time

Command:

```bash
curl -o /dev/null -s -w "%{time_total}" https://application-url
```

Reason:

Measures application performance from an end-user perspective.

---

## Process Restart Count

Reason:

Identifies unstable services that frequently crash and restart.

---

# Monitoring Flow Summary

```text
1. Load active servers from database
        |
        v
2. Connect to server via SSH
        |
        v
3. Check connectivity
        |
        v
4. Update heartbeat
        |
        v
5. Check monitored services
        |
        v
6. Update services status
        |
        v
7. Collect CPU and memory metrics
        |
        v
8. Store metrics in server_metrics
        |
        v
9. Evaluate alert rules
        |
        v
10. Generate alerts if necessary
        |
        v
11. Display information in the web dashboard
```

## Conclusion

The proposed solution uses a simple and scalable monitoring architecture based on a PHP CLI monitoring agent executed by cron jobs. The agent connects to remote servers using SSH, collects availability, service, CPU, and memory information, and stores the results in a centralized MySQL database.

The web dashboard consumes this information to provide historical metrics, service status, server health information, and alert management. This architecture is easy to implement, requires minimal infrastructure, fully leverages the proposed database model, and can be extended with additional metrics such as disk usage, network traffic, and application response time.
