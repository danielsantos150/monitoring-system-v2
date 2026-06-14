<?php require 'partials/header.php'; ?>

<div class="container">

    <header>
        <h1>Server Monitoring Dashboard</h1>

        <button onclick="openCreateModal()">
            + New Server
        </button>
    </header>

    <section class="cards">

        <div class="card">
            <h3>Total Servers</h3>
            <span id="totalServers">0</span>
        </div>

        <div class="card">
            <h3>Active</h3>
            <span id="activeServers">0</span>
        </div>

        <div class="card">
            <h3>Maintenance</h3>
            <span id="maintenanceServers">0</span>
        </div>

        <div class="card">
            <h3>Inactive</h3>
            <span id="inactiveServers">0</span>
        </div>

    </section>

    <section class="table-section">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hostname</th>
                    <th>IP</th>
                    <th>Environment</th>
                    <th>Status</th>
                    <th>Services</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="serversTable">

            </tbody>

        </table>

    </section>

</div>

<div id="modal" class="modal">

    <div class="modal-content">

        <h2>Server</h2>

        <input type="hidden" id="serverId">

        <input id="hostname" placeholder="Hostname">

        <input id="ip_address" placeholder="IP Address">

        <input id="description" placeholder="Description">

        <input id="location" placeholder="Location">

        <input id="os_name" placeholder="OS">

        <input id="os_version" placeholder="OS Version">

        <input id="cpu_cores" type="number" placeholder="CPU Cores">

        <input id="total_memory_mb" type="number" placeholder="Memory MB">

        <input id="total_disk_gb" type="number" placeholder="Disk GB">

        <select id="environment">
            <option value="production">Production</option>
            <option value="staging">Staging</option>
            <option value="development">Development</option>
        </select>

        <select id="status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="maintenance">Maintenance</option>
            <option value="decommissioned">Decommissioned</option>
        </select>

        <div class="modal-buttons">
            <button onclick="saveServer()">Save</button>
            <button onclick="closeModal()">Cancel</button>
        </div>

    </div>

</div>

<div class="card">
    <h3>Open Alerts</h3>
    <span id="openAlerts">0</span>
</div>

<div class="card">
    <h3>Critical Alerts</h3>
    <span id="criticalAlerts">0</span>
</div>

<div class="card">

    <h2>Infrastructure Metrics</h2>

    <canvas id="infraChart"></canvas>

</div>

<div class="card">

    <h2>Alerts by Severity</h2>

    <canvas id="alertsChart"></canvas>

</div>

<script src="../assets/js/app.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php require 'partials/footer.php'; ?>