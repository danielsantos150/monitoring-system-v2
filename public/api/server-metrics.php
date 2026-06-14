<?php

header('Content-Type: application/json');

require_once '../../src/MetricRepository.php';

$serverId =
    (int)$_GET['server_id'];

$metricTypeId =
    (int)$_GET['metric_type_id'];

$repository =
    new MetricRepository();

echo json_encode([
    'success' => true,
    'data' => $repository->getMetricsByServer(
        $serverId,
        $metricTypeId
    )
]);
