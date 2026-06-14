# Monitoring System V2

Technical assessment project containing the design and implementation of a server monitoring system, a web management interface, monitoring automation processes, deployment strategies, and supporting documentation.

## Overview

This project implements a monitoring platform capable of:

- Registering monitored servers
- Monitoring server availability
- Monitoring services/processes
- Collecting CPU and memory metrics
- Managing alert rules
- Generating alerts
- Displaying monitoring information through a web interface
- Supporting automated monitoring through a PHP-based monitoring agent

The solution was developed using PHP, MySQL, Docker, HTML, CSS, and JavaScript.

---

## Repository Structure

```text
monitoring-system-v2/
│
├── database/                # Database-related resources
├── public/                  # Public web files
├── python/                  # Python CLI application (Question 3)
├── src/                     # PHP source code
│
├── schema.sql               # Database schema
├── docker-compose.yml       # Docker environment
├── Dockerfile               # Application container
│
├── QUESTION_1_A.md          # Database modeling
├── QUESTION_1_B.md          # Web application design
├── QUESTION_1_C.md          # Monitoring process
├── QUESTION_2.md            # Upgrade strategy
├── QUESTION_3.md            # Python CLI deployment
├── QUESTION_4.md            # Automation philosophy
├── QUESTION_5.md            # Question 5 answer
├── QUESTION_6.md            # Runtime degradation investigation
│
├── AI_USAGE.md              # AI usage declaration
└── README.md
```

---

## Technologies

### Backend

- PHP 8.x
- PDO
- MySQL

### Frontend

- HTML5
- CSS3
- JavaScript (Vanilla JS)

### Infrastructure

- Docker
- Docker Compose

### Database

- MySQL 8

---

## Database Setup

Create the database schema using:

```bash
mysql -u root -p < schema.sql
```

Or import the schema directly into your MySQL instance.

---

## Running the Application

Build and start the containers:

```bash
docker compose up --build -d
```

Verify containers:

```bash
docker compose ps
```

Access the application:

```text
http://localhost:8080
```

---

## Monitoring Architecture

The monitoring solution uses a centralized monitoring agent.

### Monitoring Flow

```text
Monitoring Agent
       |
       v
Retrieve Servers
       |
       v
Check Availability
       |
       v
Check Services
       |
       v
Collect Metrics
       |
       v
Store Results
       |
       v
Generate Alerts
       |
       v
Web Dashboard
```

### Metrics Collected

- Server availability
- Service status
- CPU usage
- Memory usage

### Additional Metrics Suggested

- Disk utilization
- Disk I/O
- Network traffic
- Load average
- SSL certificate expiration
- Application response time

---

## SSH-Based Monitoring

The proposed monitoring implementation uses SSH connections to execute commands remotely on monitored servers.

Examples:

```bash
ssh <monitoring-user>@<server-ip> "free -m"
```

```bash
ssh <monitoring-user>@<server-ip> "top -bn1"
```

```bash
ssh <monitoring-user>@<server-ip> "systemctl is-active nginx"
```

This approach keeps the architecture simple while allowing centralized monitoring.

---

## Question References

### Question 1A

Database schema design:

```text
QUESTION_1_A.md
```

### Question 1B

Web application architecture and implementation:

```text
QUESTION_1_B.md
```

### Question 1C

Monitoring process implementation:

```text
QUESTION_1_C.md
```

### Question 2

Critical system upgrade strategy including:

- Blue-Green Deployment
- Feature Flags
- Rollback Strategy
- Backward-Compatible Persistence

```text
QUESTION_2.md
```

### Question 3

Python CLI deployment strategy.

The practical implementation related to this question can be found in:

```text
python/
```

Additional details are available in:

```text
QUESTION_3.md
```

### Question 4

Automation decision criteria:

```text
QUESTION_4.md
```

### Question 5

See:

```text
QUESTION_5.md
```

### Question 6

Runtime degradation investigation process:

```text
QUESTION_6.md
```

---

## AI Usage

AI tools were used as supporting resources during the completion of this assessment.

Details can be found in:

```text
AI_USAGE.md
```

Tools used:

- ChatGPT
- Claude Code
- Gemini

AI-generated content was reviewed, adapted, and validated before inclusion.

---

## Notes

This repository contains both the implementation and the written responses for the assessment. The code, documentation, and architectural decisions were designed to demonstrate practical approaches to monitoring, deployment, automation, and operational reliability.