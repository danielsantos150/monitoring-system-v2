let servers = [];

window.onload = () => {
    loadDashboard();
    loadServers();
    loadInfrastructureChart();
    loadAlertsChart();
};

async function loadDashboard() {
    const response =
        await fetch('../../api/dashboard.php');

    const data =
        await response.json();

    document.getElementById('totalServers').innerText =
        data.totalServers;

    document.getElementById('activeServers').innerText =
        data.activeServers;

    document.getElementById('maintenanceServers').innerText =
        data.maintenanceServers;

    document.getElementById('inactiveServers').innerText =
        data.inactiveServers;

    document.getElementById('openAlerts').innerText = data.openAlerts;

    document.getElementById('criticalAlerts').innerText = data.criticalAlerts;
}

async function loadServers() {
    const response =
        await fetch('../../api/server-list.php');

    const result =
        await response.json();

    servers = result.data;

    const table =
        document.getElementById('serversTable');

    table.innerHTML = '';

    servers.forEach(server => {

        table.innerHTML += `
            <tr>

                <td>${server.id}</td>

                <td>${server.hostname}</td>

                <td>${server.ip_address}</td>

                <td>${server.environment}</td>

                <td>${server.status}</td>
                
                <td>
                    <a href="services.php?server_id=${server.id}">
                        Services
                    </a>
                </td>

                <td>

                    <a
                        class="action-btn"
                        href="server-details.php?id=${server.id}">
                        View
                    </a>

                    <button
                        class="action-btn edit-btn"
                        onclick="editServer(${server.id})">
                        Edit
                    </button>

                    <button
                        class="action-btn delete-btn"
                        onclick="deleteServer(${server.id})">
                        Delete
                    </button>

                </td>

            </tr>
        `;
    });
}

function openCreateModal() {
    document.getElementById('serverId').value = '';

    document.getElementById('modal').style.display =
        'block';
}

function closeModal() {
    document.getElementById('modal').style.display =
        'none';
}

function editServer(id) {
    const server =
        servers.find(s => s.id == id);

    document.getElementById('serverId').value =
        server.id;

    document.getElementById('hostname').value =
        server.hostname;

    document.getElementById('ip_address').value =
        server.ip_address;

    document.getElementById('description').value =
        server.description ?? '';

    document.getElementById('location').value =
        server.location ?? '';

    document.getElementById('os_name').value =
        server.os_name ?? '';

    document.getElementById('os_version').value =
        server.os_version ?? '';

    document.getElementById('cpu_cores').value =
        server.cpu_cores ?? '';

    document.getElementById('total_memory_mb').value =
        server.total_memory_mb ?? '';

    document.getElementById('total_disk_gb').value =
        server.total_disk_gb ?? '';

    document.getElementById('environment').value =
        server.environment;

    document.getElementById('status').value =
        server.status;

    document.getElementById('modal').style.display =
        'block';
}

async function saveServer() {
    const payload = {

        id:
            document.getElementById('serverId').value,

        hostname:
            document.getElementById('hostname').value,

        ip_address:
            document.getElementById('ip_address').value,

        description:
            document.getElementById('description').value,

        location:
            document.getElementById('location').value,

        os_name:
            document.getElementById('os_name').value,

        os_version:
            document.getElementById('os_version').value,

        cpu_cores:
            document.getElementById('cpu_cores').value,

        total_memory_mb:
            document.getElementById('total_memory_mb').value,

        total_disk_gb:
            document.getElementById('total_disk_gb').value,

        environment:
            document.getElementById('environment').value,

        status:
            document.getElementById('status').value
    };

    const endpoint =
        payload.id
            ? '../../api/server-update.php'
            : '../../api/server-create.php';

    await fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    closeModal();

    loadServers();

    loadDashboard();
}

async function deleteServer(id) {
    if (!confirm('Delete server?'))
        return;

    await fetch(
        '../../api/server-delete.php',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id
            })
        }
    );

    loadServers();

    loadDashboard();
}

async function loadInfrastructureChart() {
    const response =
        await fetch(
            '../api/dashboard-metrics.php'
        );

    const result =
        await response.json();

    const labels =
        result.data.map(
            x => x.name
        );

    const values =
        result.data.map(
            x => x.average_value
        );

    const ctx =
        document
            .getElementById('infraChart')
            .getContext('2d');

    new Chart(ctx, {

        type: 'bar',

        data: {

            labels,

            datasets: [
                {
                    label: 'Average Usage',
                    data: values
                }
            ]
        }
    });
}

async function loadAlertsChart() {
    const response =
        await fetch(
            '../api/alerts-summary.php'
        );

    const result =
        await response.json();

    const labels =
        result.data.map(
            x => x.severity
        );

    const values =
        result.data.map(
            x => x.total
        );

    const ctx =
        document
            .getElementById('alertsChart')
            .getContext('2d');

    new Chart(ctx, {
        type: 'pie',

        data: {
            labels,
            datasets: [
                {
                    data: values
                }
            ]
        }
    });
}
