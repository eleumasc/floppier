<?php

require_once('functions/common.php');

continue_if_logged_in();

require_once('functions/files.php');

function upload() {
    if (!isset($_FILES['uploaded-file'])) {
        return 1;
    }

    $uploaded_file = $_FILES['uploaded-file'];

    if ($uploaded_file['error'] !== UPLOAD_ERR_OK) {
        return 2;
    }

    if (!there_is_enough_space($_SESSION['user']['id'], $uploaded_file)) {
        return 3;
    }

    $db = database_connection();

    $stat = $db->prepare('INSERT INTO `files` (`owner`, `name`, `type`, `size`, `integrity`) VALUES (:owner, :name, :type, :size, :integrity)');
    
    $stat->execute([
        'owner' => $_SESSION['user']['id'],
        'name' => $uploaded_file['name'],
        'type' => $uploaded_file['type'],
        'size' => $uploaded_file['size'],
        'integrity' => calculate_file_hash($uploaded_file['tmp_name'])
    ]);

    move_uploaded_file($uploaded_file['tmp_name'], __DIR__ . '/uploads/' . $db->lastInsertId());

    return 0;
}

if (isset($_POST['upload'])) {
    $result = upload();
    if (!$result) {
        push_flash_message('success', 'The file has been uploaded successfully!');
    } else {
        switch ($result) {
            case 1:
                $text = 'Invalid form.';
            break;
            case 2:
                $text = 'An error has occurred while uploading the file.';
            break;
            case 3:
                $text = 'There is no enough space to store this file.';
            break;
            default:
                $text = 'Unknown error.';
        }
        push_flash_message('error', $text);
    }
}

redirect_to('floppy.php');
