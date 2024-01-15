<?php
require_auth();
session_destroy();
header('Location: /login?logout');