let localData = { "rules": [], "conditions": [] };
let valueMapping = {
    "moisture": "<i class='bi bi-moisture me-2'></i>Talaj-nedvesség",
    "light": "<i class='bi bi-sun me-2'></i>Fényszint",
    "temperature": "<i class='bi bi-thermometer-half me-2'></i>Hőmérséklet",
    "humidity": "<i class='bi bi-water me-2'></i>Páratartalom",
    "time": "<i class='bi bi-clock me-2'></i>Idő"
};

//
// Local data
//
function getRuleCount() {
    if (localData["rules"].length == 0)
        return 0;
    return Math.max(...localData["rules"]);
}

function loadData(ruleData) {
    ruleData.forEach(element => {
        if (!localData["rules"].includes(Number(element["ruleID"]))) {
            localData["rules"].push(Number(element["ruleID"]));
        }
        localData["conditions"].push(element);
    });

    console.log("loadData");
    console.log(localData);
}

function getCond(condID) {
    return localData["conditions"].find(element => element["ID"] == condID) || false;
}

function getConds(ruleID) {
    let temp = [];
    localData["conditions"].forEach(element => {
        if (element["ruleID"] == ruleID)
            temp.push(element);
    });
    return temp;
}

//
// Conditions
//
function addCond(ruleID) {
    $("#modalLabel").text("Új feltétel hozzáadása a(z) " + ruleID + ". szabályhoz");
    $("#condValueSelect").val(Object.keys(valueMapping)[0]).change();
    $("#condOpSelect").val($("#condOpSelect option:first").val()).change();
    $("#condVal1").val("");
    $("#condVal2").val("");
    $("#condRuleID").val(ruleID);
    $("#condID").val(-1);
    $("#condAction").val("add");
    showModal();
}

function editCond(condID) {
    let cond = getCond(condID);
    console.log(condID);
    console.log(cond);
    let expr = cond["conditionexpr"].split(" ");

    $("#modalLabel").text("Feltétel szerkesztése");
    $("#condValueSelect").val(cond["value"]).change();
    $("#condOpSelect").val(expr[0]).change();
    $("#condVal1").val(expr[1]);
    $("#condVal2").val(expr.length > 2 ? expr[2] : "");
    $("#condRuleID").val(cond["ruleID"]);
    $("#condID").val(cond["ID"]);
    $("#condAction").val("edit");
    showModal();
}

function delCond(condID) {
    $.ajax({
        url: '/api/rules?id=' + condID,
        type: 'DELETE',
        success: function(response) {
            if(response) {
                let ruleBody = $("#cond" + condID).parent();
                $("#cond" + condID).remove();

                if (ruleBody.find(".cond-item").length == 0) {
                    loadRules();
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}

function loadCond(parent, data) {
    console.log(data);
    parent.find("#placeholderCond").remove();

    $(".cond-item.d-none").clone().appendTo(parent);

    parent.find(".cond-item.d-none").attr("id", "cond" + data["ID"]);
    let conditem = $("#cond" + data["ID"]);
    conditem.find(".cond-title").html(valueMapping[data["value"]]);
    conditem.find(".cond-text").text(data["conditionexpr"]);
    conditem.find("#delCond").attr("onclick","delCond(" + data["ID"] + ");");
    conditem.find("#editCond").attr("onclick","editCond(" + data["ID"] + ");");
    conditem.removeClass("d-none");
}

//
// Rules
//
function addRule(ruleID) {
    if (ruleID > 1) {
        $(".or-divider.d-none").clone().appendTo("#ruleDisplay");
        $("#ruleDisplay > .or-divider").removeClass("d-none");
    }

    $(".rule-card.d-none").clone().appendTo("#ruleDisplay");
    $("#ruleDisplay > .rule-card:last").attr("id", "rule" + ruleID);
    $("#rule" + ruleID + " > .card-header > span").text(ruleID + ". szabály");
    $("#rule" + ruleID + " > .card-footer > .btn").attr("onclick","addCond(" + ruleID + ");");

    getConds(ruleID).forEach(element => {
        loadCond($("#rule" + ruleID + " > .rule-body"), element);
    });

    $("#rule" + ruleID).removeClass("d-none");
}

function newRule() {
    let nextRuleID = getRuleCount() + 1;
    localData["rules"].push(nextRuleID);
    addRule(nextRuleID);
    $("#rule" + nextRuleID + " > .card-header > .btn-close").show();
    $("#rule" + nextRuleID + " > .card-header > .btn-close").click(function() {
        $("#rule" + nextRuleID).prev().remove();
        $("#rule" + nextRuleID).remove();
        localData["rules"].pop();
        $("#btnAddRule").prop('disabled', false);
    });
    $("#btnAddRule").prop('disabled', true);
}

function loadRules() {
    localData["rules"] = [];
    localData["conditions"] = [];
    $("#ruleDisplay").empty();

    $.get("/api/rules")
    .done(function(data){
        console.log("loadRules")
        console.log(data);
        loadData(data);
        for (let i = 1; i <= getRuleCount(); i++) {
            addRule(i);
        }
        $("#btnAddRule").prop('disabled', false);
    });
}

//
// Errors
//
function showCondError(text) {
    $("#condError").text(text);
    $("#condError").show();
}

//
// Events
//
function showModal() {
    $("#condError").hide();
    $('#ruleModal').modal('show');
}

$( document ).ready(function() {
    loadRules();

    $.each(valueMapping, function(value, html) {
        let optionText = html.substring(html.lastIndexOf('>') + 1);
        $('#condValueSelect').append($('<option>', { 
            value: value,
            text : optionText 
        }));
    });

    $("#formCond").submit(function() {
        let valid = document.getElementById('formCond').checkValidity();
        
        if (valid) {
            let condData = {
                "action": $("#condAction").val(),
                "condID": $("#condID").val(),
                "ruleID": $("#condRuleID").val(),
                "value": $("#condValueSelect").val(),
                "conditionexpr": $("#condOpSelect").val() + " " + $("#condVal1").val() +
                ($("#condOpSelect").val() == "BETWEEN" ? (" " + $("#condVal2").val()) : "")
            };
            console.log(condData);
            $.post("/api/rules", condData)
            .done(function(data){
                console.log(data);
                if (data == true) {
                    loadRules();
                    $('#ruleModal').modal('hide');
                }
            })
            .fail(function(jqXHR, textStatus, error) {
                console.log(jqXHR.responseJSON);
                showCondError("Hiba történt! " + jqXHR.responseJSON["error"])
            });
        }

        return false;
    });
    $("#condValueSelect").change(function() {
        if ($("#condValueSelect").val() == "time") {
            $("#condVal1").prop('type', 'time');
            $("#condVal2").prop('type', 'time');
        }
        else {
            $("#condVal1").prop('type', 'number');
            $("#condVal2").prop('type', 'number');
        }
    });
    $("#condOpSelect").change(function() {
        if ($("#condOpSelect").val() == "BETWEEN") {
            $("#condVal2").show();
            $("#condVal2").prop('required',true);
        }
        else {
            $("#condVal2").hide();
            $("#condVal2").prop('required',false);
        }
    });

    $("#btnAddRule").click(function() {
        newRule();
    });
});