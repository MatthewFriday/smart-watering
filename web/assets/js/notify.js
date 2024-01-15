$( document ).ready(function() {
    $("#poverEnable").change(function() {
        $("#poverToken").prop('disabled', !this.checked);
        $("#poverUserKey").prop('disabled', !this.checked);
    });

    $("#formNotify").submit(function() {
        let valid = document.getElementById('formNotify').checkValidity();
        
        if (valid) {
            $("#alertSuccess").hide("fast");
            $("#alertError").hide("fast");

            config = {
                "poverEnable": $("#poverEnable").prop('checked'),
                "poverToken": $("#poverToken").val(),
                "poverUserKey": $("#poverUserKey").val()
            }
            console.log(config);
            $.post("/api/set_notify", config)
            .done(function(data){
                console.log(data);
                if (data == true) {
                    $("#alertSuccess").show("fast");
                } else {
                    $("#err").html(data);
                    $("#alertError").show("fast");
                }
            })
            .fail(function(jqXHR, textStatus, error) {
                $("#err").html(jqXHR.responseJSON);
                $("#alertError").show("fast");
            });
        }

        return false;
    });

    $("#poverEnable").change();
});