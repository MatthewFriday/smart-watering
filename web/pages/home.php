<?php
require_auth();
require_once("navbar.php");
$data = $db->get_latest_measurements();
?>
<main class="container">
    <div class="bg-body-tertiary p-5 rounded">
        <h1>Aktuális állapot</h1>
        <div class="card my-3">
            <ul class="list-group list-group-flush">
                <li class="list-group-item fs-5"><i class="bi bi-moisture me-2"></i>Talaj-nedvesség: <span class="font-monospace fw-bold" id="valMoisture"><?php echo $data["moisture"]; ?></span></li>
                <li class="list-group-item fs-5"><i class="bi bi-sun me-2"></i>Fényszint: <span class="font-monospace fw-bold" id="valLight"><?php echo $data["light"]; ?></span></li>
                <li class="list-group-item fs-5"><i class="bi bi-thermometer-half me-2"></i>Hőmérséklet: <span class="font-monospace fw-bold" id="valTemperature"><?php echo $data["temperature"]; ?>°C</span></li>
                <li class="list-group-item fs-5"><i class="bi bi-water me-2"></i>Páratartalom: <span class="font-monospace fw-bold" id="valHumidity"><?php echo $data["humidity"]; ?>%</span></li>
                <li class="list-group-item fs-5"><i class="bi bi-droplet me-2"></i>Szelep: <span class="badge text-bg-<?php echo $data["relay"] ? "success" : "danger"; ?>" id="valRelay"><?php echo $data["relay"] ? "Nyitva" : "Zárva"; ?></span></li>
            </ul>
        </div>
        <a class="btn btn-lg btn-primary" href="#" id="btnUpdate" role="button">Frissítés</a>
        <p class="text-muted my-3">Utolsó frissítés: <span class="font-monospace" id="valUpdate"><?php echo date("Y-m-d H:i:s"); ?></span></p>
    </div>
</main>