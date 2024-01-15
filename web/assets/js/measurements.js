function loadMeasurements() {
    $("#error").hide();
    $(".loader").show();
    $("#btnQuery").prop('disabled', true);
    $.get("/api/all_measurements", { start: $("#dt_start").val(), end: $("#dt_end").val()})
    .done(function(data) {
        let x_timestamp = [];
        let y_moisture = [];
        let y_light = [];
        let y_temperature = [];
        let y_humidity = [];
        let y_relay = [];

        data.forEach(element => {
            x_timestamp.push(element["timestamp"]);
            y_moisture.push(element["humidity"]);
            y_light.push(element["light"]);
            y_temperature.push(element["temperature"]);
            y_humidity.push(element["humidity"]);
            y_relay.push(element["relay"]);
        });
        $("#queryCount").text(data.length)

        let moisture_data = [{x:x_timestamp, y:y_moisture, mode:"lines"}];
        let light_data = [{x:x_timestamp, y:y_light, mode:"lines"}];
        let temperature_data = [{x:x_timestamp, y:y_temperature, mode:"lines"}];
        let humidity_data = [{x:x_timestamp, y:y_humidity, mode:"lines"}];
        let relay_data = [{x:x_timestamp, y:y_relay, mode:"lines"}];

        let layout = {
            //paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor: 'rgba(0,0,80,0.02)',
            margin: { l:50, r:50, t:50, b:50 }
        };

        let config = { responsive: true }

        Plotly.newPlot("plotMoisture", moisture_data, layout, config);
        Plotly.newPlot("plotLight", light_data, layout, config);
        Plotly.newPlot("plotTemperature", temperature_data, layout, config);
        Plotly.newPlot("plotHumidity", humidity_data, layout, config);
        Plotly.newPlot("plotRelay", relay_data, layout, config);

        
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        $("#error").text("Hiba: " + errorThrown);
        $("#error").show();
    })
    .always(function() {
        $(".loader").hide();
        $("#btnQuery").prop('disabled', false);
    });
}

$( document ).ready(function() {
    loadMeasurements();
    $("#btnQuery").click(loadMeasurements);
});