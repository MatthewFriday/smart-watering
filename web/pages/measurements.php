<?php
require_auth();
require_once("navbar.php");
$stats = $db->get_measurement_stats();
$mindt = strtotime($stats["min_timestamp"]);
$maxdt = strtotime($stats["max_timestamp"]);
$startdt = strtotime("-12 hours", $maxdt);
if ($startdt < $mindt) {
    $startdt = $mindt;
}
?>
<main class="container-fluid mx-2">
    <div class="bg-body-tertiary p-5 rounded">
        <h1 class="mb-3">Mérési adatok</h1>
        <div class="row flex-sm-row mb-3">
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-text" id="txt_start">Kezdet</span>
                    <input class="form-control" type="datetime-local" id="dt_start" name="dt_start" required
                        aria-describedby="txt_start"
                        min="<?php echo date("Y-m-d\TH:i:s", $mindt); ?>"
                        max="<?php echo date("Y-m-d\TH:i:s", $maxdt); ?>"
                        value="<?php echo date("Y-m-d\TH:i:s", $startdt); ?>">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-text" id="txt_end">Vége</span>
                    <input class="form-control" type="datetime-local" id="dt_end" name="dt_end" required
                        aria-describedby="txt_end"
                        min="<?php echo date("Y-m-d\TH:i:s", $mindt); ?>"
                        max="<?php echo date("Y-m-d\TH:i:s", $maxdt); ?>"
                        value="<?php echo date("Y-m-d\TH:i:s", $maxdt); ?>">
                </div>
            </div>
        </div>
        <button class="btn btn-primary w-100 mb-3" type="button" id="btnQuery">Lekérdezés</button>
        <div id="error" class="alert alert-danger" role="alert" style="display:none;">
            A simple danger alert—check it out!
        </div>
        <div class="accordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="panel1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                        Talaj-nedvesség
                    </button>
                </h2>
                <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="panel1">
                    <div class="accordion-body">
                        <div class="loader" id="loader1">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border me-2"></div>
                                <strong role="status">Loading...</strong>
                            </div>
                        </div>
                        <div id="plotMoisture" style="width:100%"></div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="panel2">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                        Fényszint
                    </button>
                </h2>
                <div id="collapse2" class="accordion-collapse collapse show" aria-labelledby="panel2">
                    <div class="accordion-body">
                        <div class="loader" id="loader2">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border me-2"></div>
                                <strong role="status">Loading...</strong>
                            </div>
                        </div>
                        <div id="plotLight" style="width:100%"></div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="panel3">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                        Hőmérséklet
                    </button>
                </h2>
                <div id="collapse3" class="accordion-collapse collapse show" aria-labelledby="panel3">
                    <div class="accordion-body">
                        <div class="loader" id="loader3">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border me-2"></div>
                                <strong role="status">Loading...</strong>
                            </div>
                        </div>
                        <div id="plotTemperature" style="width:100%"></div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="panel4">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="true" aria-controls="collapse4">
                        Páratartalom
                    </button>
                </h2>
                <div id="collapse4" class="accordion-collapse collapse show" aria-labelledby="panel4">
                    <div class="accordion-body">
                        <div class="loader" id="loader4">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border me-2"></div>
                                <strong role="status">Loading...</strong>
                            </div>
                        </div>
                        <div id="plotHumidity" style="width:100%"></div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="panel5">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="true" aria-controls="collapse5">
                        Szelep
                    </button>
                </h2>
                <div id="collapse5" class="accordion-collapse collapse show" aria-labelledby="panel5">
                    <div class="accordion-body">
                        <div class="loader" id="loader5">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border me-2"></div>
                                <strong role="status">Loading...</strong>
                            </div>
                        </div>
                        <div id="plotRelay" style="width:100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center my-3">
            <span class="badge text-bg-secondary">Mérések az adatbázisban: <?php echo $stats["count"]; ?> | Megjelenített mérések: <span id="queryCount">0</span></span>
        </div>
    </div>
</main>