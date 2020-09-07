<?php

require_once('functions/common.php');

continue_if_logged_in();

require_once('functions/files.php');

if (!isset($_GET['fileid'])) {
    http_response_code(400);
    die;
}

$fileid = $_GET['fileid'];

$file = get_file_by_id((int)$fileid);

if (!$file) {
    http_response_code(404);
    die;
}

if ($file['owner'] !== $_SESSION['user']['id']) {
    http_response_code(404);
    die;
}

$file_path = __DIR__ . '/uploads/' . $fileid;

if (calculate_file_hash($file_path) !== $file['integrity']) {
    http_response_code(500);
    echo 'Integrity check failed.';
    die;
}

header('Content-Type: ' . $file['type']);
header('Content-Disposition: attachment; filename="' . $file['name'] . '"');

readfile($file_path);
