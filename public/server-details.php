<?php require 'partials/header.php'; ?>

<div class="container">

    <a href="index.php">
        ← Back
    </a>

    <h1 id="hostname">
        Loading...
    </h1>

    <div class="details-grid">

        <div class="card">

            <h3>IP Address</h3>

            <span id="ipAddress"></span>

        </div>

        <div class="card">

            <h3>Environment</h3>

            <span id="environment"></span>

        </div>

        <div class="card">

            <h3>Status</h3>

            <span id="status"></span>

        </div>

        <div class="card">

            <h3>CPU Cores</h3>

            <span id="cpuCores"></span>

        </div>

    </div>

    <br>

    <div class="card">

        <h2>Description</h2>

        <p id="description"></p>

    </div>

    <br>

    <div class="card">

        <h2>Services</h2>

        <table>

            <thead>

                <tr>
                    <th>Name</th>
                    <th>Port</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody id="servicesTable">

            </tbody>

        </table>

    </div>

    <br>

    <div class="card">

        <h2>Recent Alerts</h2>

        <table>

            <thead>

                <tr>

                    <th>Severity</th>
                    <th>Status</th>
                    <th>Message</th>

                </tr>

            </thead>

            <tbody id="alertsTable">

            </tbody>

        </table>

    </div>

    <br>

    <div class="card">

        <h2>CPU Usage</h2>

        <canvas id="cpuChart"></canvas>

    </div>

    <br>

    <div class="card">

        <h2>Memory Usage</h2>

        <canvas id="memoryChart"></canvas>

    </div>

    <br>

    <div class="card">

        <h2>Disk Usage</h2>

        <canvas id="diskChart"></canvas>

    </div>

</div>

<script src="../assets/js/server-details.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php require 'partials/footer.php'; ?>