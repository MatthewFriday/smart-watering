<?php
require_auth();

$result = $db->set_notify_config($_POST);

if ($result == false)
    json_return(array("result" => false, "error" => $db->get_last_error()), 400);
else
    json_return($result);