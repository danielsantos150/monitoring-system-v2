const params =
    new URLSearchParams(
        window.location.search
    );

const serverId =
    params.get('id');

let cpuChart;
let memoryChart;
let diskChart;

window.onload = async () => {

    await loadServer();

    await loadServices();

    await loadAlerts();

    await loadMetricChart(
        1,
        'cpuChart',
        'CPU Usage'
    );

    await loadMetricChart(
        2,
        'memoryChart',
        'Memory Usage'
    );

    await loadMetricChart(
        3,
        'diskChart',
        'Disk Usage'
    );
};

async function loadServer() {
    const response =
        await fetch(
            `../../api/server-details.php?id=${serverId}`
        );

    const result =
        await response.json();

    if (!result.data?.length) {
        table.innerHTML =
            `
        <tr>
            <td colspan="10">
                No records found
            </td>
        </tr>
        `;

        return;
    }
    const server =
        result.data;

    document.getElementById(
        'hostname'
    ).innerText =
        server.hostname;

    document.getElementById(
        'ipAddress'
    ).innerText =
        server.ip_address;

    document.getElementById(
        'environment'
    ).innerText =
        server.environment;

    document.getElementById(
        'status'
    ).innerText =
        server.status;

    document.getElementById(
        'cpuCores'
    ).innerText =
        server.cpu_cores ?? '-';

    document.getElementById(
        'description'
    ).innerText =
        server.description ?? '-';
}

async function loadServices() {
    const response =
        await fetch(
            `../../api/service-list.php?server_id=${serverId}`
        );

    const result =
        await response.json();

    if (!result.data?.length) {
        table.innerHTML =
            `
        <tr>
            <td colspan="10">
                No records found
            </td>
        </tr>
        `;

        return;
    }

    const table =
        document.getElementById(
            'servicesTable'
        );

    table.innerHTML = '';

    result.data.forEach(service => {

        table.innerHTML += `
            <tr>

                <td>${service.name}</td>

                <td>${service.port ?? '-'}</td>

                <td>${service.status}</td>

            </tr>
        `;
    });
}

async function loadAlerts() {
    const response =
        await fetch(
            `../../api/server-alerts.php?server_id=${serverId}`
        );

    const result =
        await response.json();

    if (!result.data?.length) {
        table.innerHTML =
            `
        <tr>
            <td colspan="10">
                No records found
            </td>
        </tr>
        `;

        return;
    }

    const table =
        document.getElementById(
            'alertsTable'
        );

    table.innerHTML = '';

    result.data.forEach(alert => {

        table.innerHTML += `
            <tr>

                <td>${alert.severity}</td>

                <td>${alert.status}</td>

                <td>${alert.message ?? ''}</td>

            </tr>
        `;
    });
}

async function loadMetricChart(
    metricTypeId,
    canvasId,
    label
) {
    const response =
        await fetch(
            `../api/server-metrics.php?server_id=${serverId}&metric_type_id=${metricTypeId}`
        );

    const result =
        await response.json();

    const labels =
        result.data.map(
            x => x.collected_at
        );

    const values =
        result.data.map(
            x => x.value
        );

    const ctx =
        document
            .getElementById(canvasId)
            .getContext('2d');

    new Chart(ctx, {

        type: 'line',

        data: {

            labels,

            datasets: [

                {
                    label,
                    data: values,
                    borderWidth: 2,
                    tension: 0.3
                }

            ]
        },

        options: {

            responsive: true,

            scales: {

                y: {

                    beginAtZero: true
                }
            }
        }
    });
}