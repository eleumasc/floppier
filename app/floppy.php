<?php

require_once('functions/common.php');

continue_if_logged_in();

require_once('functions/files.php');

$files = get_files_by_user($_SESSION['user']['id']);

$used_space = get_used_space_by_user($_SESSION['user']['id']);

include('templates/floppy-page.php');
