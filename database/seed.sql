INSERT INTO metric_types
(name,unit,description)
VALUES

('CPU Usage','%','CPU Usage'),

('Memory Usage','%','Memory Usage'),

('Disk Usage','%','Disk Usage');

INSERT INTO servers
(
    hostname,
    ip_address,
    environment,
    status
)
VALUES
(
    'web-prod-01',
    '10.0.0.10',
    'production',
    'active'
);