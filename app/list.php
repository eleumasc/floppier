<?php

require_once('functions/common.php');

continue_if_logged_in();

require_once('functions/files.php');

if (!isset($_GET['userid'])) {
    http_response_code(400);
    die;
}

$userid = $_GET['userid'];

$files = get_files_by_user((int)$userid);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="files.csv"');

echo "id;name;type;size;integrity\n";

foreach ($files as $file) {
    echo "{$file['id']};{$file['name']};{$file['type']};{$file['size']};{$file['integrity']}\n";
}
