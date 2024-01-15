<?php
require_auth();

if ($_SERVER['REQUEST_METHOD'] == "GET")
    json_return($db->get_rules());
else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $result = null;

    if (!isset($_POST["action"]))
        json_return(array("result" => false, "error" => "Missing action"), 400);

    if ($_POST["action"] == "add")
        $result = $db->add_rule($_POST);
    elseif ($_POST["action"] == "edit")
        $result = $db->edit_rule($_POST);
    
    if (isset($result)) {
        if ($result == false)
            json_return(array("result" => false, "error" => $db->get_last_error()), 400);
        else
            json_return($result);
    }

    json_return(array("result" => false, "error" => "Unknown action"), 400);
}
else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    json_return($db->del_rule($_GET["id"]));
}