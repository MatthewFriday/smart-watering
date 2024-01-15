<?php
session_start();
$config = parse_ini_file("config.ini");
$user = null;

require_once('database.php');
$db = new Database($config["user"], $config["pass"], $config["host"], $config["database"]);

function require_auth() {
    global $user, $db;
    
    if (!isset($_SESSION['ID'])) {
        header('Location: /login');
        die();
    }
    $user = $db->get_user($_SESSION['ID']);
    if (!$user) {
        header('Location: /logout');
        die();
    }
}
function json_return($data, $code = 200) {
    header('Content-Type: application/json');
    echo json_encode($data);
    http_response_code($code);
    exit();
}

$parts = explode("/", trim($_SERVER['REDIRECT_URL'], "/"));

if ($parts[0] == "api" && count($parts) > 1) {
    if (file_exists("./api/$parts[1].php"))
        require_once("./api/$parts[1].php");
    else
        echo "404";
    exit();
}

require_once('header.php');

if (file_exists("./pages/$parts[0].php"))
    require_once("./pages/$parts[0].php");
else
    echo "404";

require_once('footer.php');