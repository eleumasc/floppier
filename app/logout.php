<?php

require_once('functions/common.php');

if (!isset($_POST['logout'])) {
    redirect_to('floppy.php');
}

unset($_SESSION['user']);

redirect_to('index.php');
