<?php
require_auth();
$start = $_GET['start'];
$end = $_GET['end'];
json_return($db->get_all_measurements($start, $end));