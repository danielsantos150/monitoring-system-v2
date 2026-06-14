<?php require 'partials/header.php'; ?>

<div class="container">

    <h1>Services</h1>

    <input
        type="hidden"
        id="serverId"
        value="<?= $_GET['server_id'] ?>">

    <button onclick="openServiceModal()">
        New Service
    </button>

    <table>

        <thead>
            <tr>
                <th>Name</th>
                <th>Port</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody id="servicesTable">

        </tbody>

    </table>

</div>

<div class="modal" id="serviceModal">

    <div class="modal-content">

        <input type="hidden" id="serviceId">

        <input id="serviceName"
            placeholder="Service Name">

        <input id="servicePort"
            type="number"
            placeholder="Port">

        <select id="serviceStatus">

            <option value="running">
                Running
            </option>

            <option value="stopped">
                Stopped
            </option>

            <option value="unknown">
                Unknown
            </option>

        </select>

        <div class="modal-buttons">

            <button onclick="saveService()">
                Save
            </button>

            <button onclick="closeServiceModal()">
                Cancel
            </button>

        </div>

    </div>

</div>

<script src="../assets/js/services.js"></script>

<?php require 'partials/footer.php'; ?>