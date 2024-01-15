<?php
require_auth();
require_once("navbar.php");
?>
<div class="text-center w-100 border-bottom border-2 border-dark-subtle mt-2 mb-4 or-divider d-none" style="height: 20px;">
    <span class="fs-4 bg-body-tertiary" style="padding: 0 10px;">
        VAGY
    </span>
</div>
<li class="list-group-item d-none" id="placeholderCond"><span class="text-muted">Még nincsenek feltételek!</span></li>
<div class="card rule-card my-auto mx-4 d-none">
    <div class="card-header">
        <span></span>
        <button type="button" class="btn-close float-end" style="display: none;"></button>
    </div>
    <ul class="list-group list-group-flush rule-body">
        <li class="list-group-item" id="placeholderCond"><span class="text-muted">Még nincsenek feltételek!</span></li>
    </ul>
    <div class="card-footer">
        <button class="btn btn-outline-primary m-0 w-100" type="button">
            Új feltétel hozzáadása
        </button>
    </div>
</div>
<li class="list-group-item cond-item d-none">
    <div class="row flex-sm-row">
        <div class="col-auto d-flex align-items-center"><h5 class="card-title cond-title mb-0"></h5></div>
        <div class="col-auto d-flex align-items-center"><p class="card-text cond-text mb-0 text-uppercase"></p></div>
        <div class="col"><button type="button" class="btn btn-outline-danger float-end mx-1" id="delCond">Törlés</button><button type="button" class="btn btn-outline-secondary float-end mx-1" id="editCond">Szerkesztés</button></div>
    </div>
</li>

<main class="container">
    <div class="bg-body-tertiary p-5 rounded">
        <h1 class="mb-3">Öntözési szabályok</h1>
        <div id="ruleDisplay">
        </div>
        
        <button class="btn btn-success w-100 my-3" type="button" id="btnAddRule">Új szabály hozzáadása</button>

        <!-- Modal -->
        <div class="modal fade" id="ruleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formCond">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalLabel"></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label for="condValueSelect" class="form-label">Ellenőrzött érték</label>
                            <select class="form-select mb-3" id="condValueSelect"></select>
                            <label for="condOpSelect" class="form-label">Művelet</label>
                            <select class="form-select mb-3" id="condOpSelect">
                                <option value="<">Kisebb mint (<)</option>
                                <option value="<=">Kisebb vagy egyenlő mint (<=)</option>
                                <option value="==">Egyenlő (==)</option>
                                <option value="!=">Nem egyenlő (!=)</option>
                                <option value=">=">Nagyobb vagy egyenlő mint (>=)</option>
                                <option value=">">Nagyobb mint (>)</option>
                                <option value="BETWEEN">Két érték között (BETWEEN)</option>
                            </select>
                            <label for="condVal1" class="form-label">Érték</label>
                            <input type="number" class="form-control mb-3" id="condVal1" placeholder="0" required>
                            <input type="number" class="form-control mb-3" id="condVal2" placeholder="0" style="display: none;">
                            <input type="hidden" id="condRuleID" value="-1">
                            <input type="hidden" id="condID" value="-1">
                            <input type="hidden" id="condAction" value="add">
                            <div class="alert alert-danger" style="display: none;" id="condError" role="alert"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                            <button type="submit" class="btn btn-primary" id="modalSend">Hozzáadás</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>