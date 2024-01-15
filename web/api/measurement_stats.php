<?php
require_auth();
json_return($db->get_measurement_stats());