let services = [];

window.onload = () => {
    loadServices();
};

async function loadServices() {
    const serverId =
        document.getElementById('serverId').value;

    const response =
        await fetch(
            `../../api/services-list.php?server_id=${serverId}`
        );

    const result =
        await response.json();

    services = result.data;

    const table =
        document.getElementById(
            'servicesTable'
        );

    table.innerHTML = '';

    services.forEach(service => {

        table.innerHTML += `
            <tr>

                <td>${service.name}</td>

                <td>${service.port ?? '-'}</td>

                <td>${service.status}</td>

                <td>

                    <button
                        class="edit-btn"
                        onclick="editService(${service.id})">
                        Edit
                    </button>

                    <button
                        class="delete-btn"
                        onclick="deleteService(${service.id})">
                        Delete
                    </button>

                </td>

            </tr>
        `;
    });
}

function openServiceModal() {
    document.getElementById(
        'serviceModal'
    ).style.display = 'block';
}

function closeServiceModal() {
    document.getElementById(
        'serviceModal'
    ).style.display = 'none';
}

function editService(id) {
    const service =
        services.find(
            s => s.id == id
        );

    document.getElementById(
        'serviceId'
    ).value = service.id;

    document.getElementById(
        'serviceName'
    ).value = service.name;

    document.getElementById(
        'servicePort'
    ).value = service.port;

    document.getElementById(
        'serviceStatus'
    ).value = service.status;

    openServiceModal();
}

async function saveService() {
    const payload = {

        id:
            document.getElementById(
                'serviceId'
            ).value,

        server_id:
            document.getElementById(
                'serverId'
            ).value,

        name:
            document.getElementById(
                'serviceName'
            ).value,

        port:
            document.getElementById(
                'servicePort'
            ).value,

        status:
            document.getElementById(
                'serviceStatus'
            ).value
    };

    const endpoint =
        payload.id
            ? '../../api/services-update.php'
            : '../../api/services-create.php';

    await fetch(endpoint, {

        method: 'POST',

        headers: {
            'Content-Type':
                'application/json'
        },

        body: JSON.stringify(payload)
    });

    closeServiceModal();

    loadServices();
}

async function deleteService(id) {
    if (!confirm('Delete service?'))
        return;

    await fetch(
        '../../api/services-delete.php',
        {
            method: 'POST',

            headers: {
                'Content-Type':
                    'application/json'
            },

            body: JSON.stringify({
                id: id
            })
        }
    );

    loadServices();
}