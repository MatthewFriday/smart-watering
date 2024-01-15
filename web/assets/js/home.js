function updateMeasurements() {
    $("#btnUpdate").prop('disabled', true);
    $.get("/api/latest_measurements")
    .done(function(data){
        console.log(data);
        $("#valMoisture").text(data["moisture"]);
        $("#valLight").text(data["light"]);
        $("#valTemperature").text(data["temperature"] + "°C");
        $("#valHumidity").text(data["humidity"] + "%");
        $("#valRelay").text(data["relay"] ? "Nyitva" : "Zárva");
        $("#valUpdate").text(data["timestamp"]);
    })
    .always(function() {
        $("#btnUpdate").prop('disabled', false);
    });
}

$( document ).ready(function() {
    let measurementUpdate = window.setInterval(updateMeasurements, 5000);
    $("#btnUpdate").click(updateMeasurements);
});