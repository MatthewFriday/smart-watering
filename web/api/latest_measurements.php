<?php
require_auth();
json_return($db->get_latest_measurements());