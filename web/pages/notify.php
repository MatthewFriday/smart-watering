<?php
require_auth();
require_once("navbar.php");
$data = $db->get_config();
?>
<main class="container">
    <div class="bg-body-tertiary p-5 rounded">
        <h1>Értesítési beállítások</h1>
        <form id="formNotify">
            <div class="card">
                <div class="card-header">
                    <div class="form-check  form-switch">
                        <input type="checkbox" class="form-check-input" id="poverEnable"<?php echo $data["poverEnable"] == "true" ? " checked" : ""; ?>>
                        <label class="form-check-label" for="poverEnable">Pushover értesítés engedélyezése</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="poverToken" class="form-label">Pushover alkalmazás token (AppToken)</label>
                        <input type="text" class="form-control" id="poverToken" required value="<?php echo $data["poverToken"]; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="poverUserKey" class="form-label">Pushover felhasználó kulcsa (UserKey)</label>
                        <input type="text" class="form-control" id="poverUserKey" required value="<?php echo $data["poverUserKey"]; ?>">
                    </div>
                </div>
            </div>
            <div class="alert alert-success mt-3 mb-0" style="display: none;" id="alertSuccess" role="alert">
                Beállítások sikeresen mentve!
            </div>
            <div class="alert alert-danger mt-3 mb-0" style="display: none;" id="alertError" role="alert">
                Beállítások mentése sikertelen!
                <pre id="err"></pre>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Mentés</button>
        </form>
    </div>
</main>