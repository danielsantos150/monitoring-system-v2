let rules = [];

window.onload = async () => {

    //await loadServers();

    //await loadMetricTypes();

    await loadRules();
};

async function loadRules() {
    const response =
        await fetch(
            '../../api/alert-rules-list.php'
        );

    const result =
        await response.json();

    rules = result.data;

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
            'rulesTable'
        );

    table.innerHTML = '';

    rules.forEach(rule => {

        table.innerHTML += `
            <tr>

                <td>
                    ${rule.hostname ?? 'GLOBAL'}
                </td>

                <td>
                    ${rule.metric_name}
                </td>

                <td>
                    ${rule.operator}
                </td>

                <td>
                    ${rule.threshold_value}
                </td>

                <td>
                    ${rule.severity}
                </td>

                <td>
                    ${rule.is_active}
                </td>

                <td>

                    <button
                        onclick="deleteRule(${rule.id})">
                        Delete
                    </button>

                </td>

            </tr>
        `;
    });
}