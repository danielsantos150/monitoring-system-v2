<?php require 'partials/header.php'; ?>


<div class="container">

    <h1>Alert Rules</h1>

    <button onclick="openRuleModal()">
        New Rule
    </button>

    <table>

        <thead>

            <tr>

                <th>Server</th>
                <th>Metric</th>
                <th>Operator</th>
                <th>Threshold</th>
                <th>Severity</th>
                <th>Active</th>
                <th>Actions</th>

            </tr>

        </thead>

        <tbody id="rulesTable">

        </tbody>

    </table>

</div>

<div class="modal" id="ruleModal">

    <div class="modal-content">

        <select id="serverSelect">

            <option value="">
                Global Rule
            </option>

        </select>

        <select id="metricTypeSelect">

        </select>

        <select id="operator">

            <option value=">">></option>
            <option value=">=">>=</option>
            <option value="<">
                << /option>
            <option value="<=">
                <=< /option>
            <option value="=">=</option>

        </select>

        <input
            id="threshold"
            type="number"
            step="0.01"
            placeholder="Threshold">

        <select id="severity">

            <option value="info">Info</option>
            <option value="warning">Warning</option>
            <option value="critical">Critical</option>

        </select>

        <label>

            Active

            <input
                type="checkbox"
                id="activeRule"
                checked>

        </label>

        <div class="modal-buttons">

            <button onclick="saveRule()">
                Save
            </button>

            <button onclick="closeRuleModal()">
                Cancel
            </button>

        </div>

    </div>

</div>

<script src="../assets/js/alert-rules.js"></script>

<?php require 'partials/footer.php'; ?>