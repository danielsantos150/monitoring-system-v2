<?php require 'partials/header.php'; ?>

<div class="container">

    <h1>Alerts</h1>

    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Server</th>
                <th>Severity</th>
                <th>Status</th>
                <th>Triggered</th>
                <th>Resolved</th>
                <th>Message</th>
                <th>Actions</th>

            </tr>

        </thead>

        <tbody id="alertsTable">

        </tbody>

    </table>

</div>

<script src="../assets/js/alerts.js"></script>

<?php require 'partials/footer.php'; ?>