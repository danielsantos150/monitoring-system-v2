window.onload = () => {
    loadAlerts();
};

async function loadAlerts() {
    const response =
        await fetch(
            '../../api/alerts-list.php'
        );

    const result =
        await response.json();

    const table =
        document.getElementById(
            'alertsTable'
        );

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

    table.innerHTML = '';

    result.data.forEach(alert => {

        table.innerHTML += `
            <tr>

                <td>${alert.id}</td>

                <td>${alert.hostname}</td>

                <td>${alert.severity}</td>

                <td>${alert.status}</td>

                <td>${alert.triggered_at}</td>

                <td>
                    ${alert.resolved_at ?? '-'}
                </td>

                <td>
                    ${alert.message ?? ''}
                </td>

                <td>

                    ${alert.status === 'open'
                ? `
                            <button
                                onclick="acknowledgeAlert(${alert.id})">
                                Acknowledge
                            </button>
                        `
                : ''
            }

                    ${alert.status !== 'resolved'
                ? `
                            <button
                                onclick="resolveAlert(${alert.id})">
                                Resolve
                            </button>
                        `
                : ''
            }

                </td>

            </tr>
        `;
    });
}

async function acknowledgeAlert(id) {
    await fetch(
        '../../api/alerts-acknowledge.php',
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

    loadAlerts();
}

async function resolveAlert(id) {
    await fetch(
        '../../api/alerts-resolve.php',
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

    loadAlerts();
}