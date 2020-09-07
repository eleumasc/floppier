<?php

require_once('functions/common.php');

continue_if_logged_in();

require_once('functions/files.php');

function delete() {
    if (!isset($_POST['fileid'])) {
        return 1;
    }
    
    $fileid = $_POST['fileid'];
    
    $file = get_file_by_id((int)$fileid);
    
    if (!$file) {
        return 2;
    }
    
    if ($file['owner'] !== $_SESSION['user']['id']) {
        return 3;
    }
    
    $file_path = __DIR__ . '/uploads/' . $fileid;
    
    $db = database_connection();
    
    $stat = $db->prepare('DELETE FROM `files` WHERE `id` = :id');
    
    $stat->execute([
        'id' => (int)$fileid,
    ]);
    
    unlink($file_path);
    
    return 0;
}

if (isset($_POST['delete'])) {
    $result = delete();
    if (!$result) {
        push_flash_message('success', 'The file has been deleted successfully!');
    } else {
        switch ($result) {
            case 1:
                $text = 'Invalid form.';
            break;
            case 2:
                $text = 'File not found.';
            break;
            case 3:
                $text = 'File not found.';
            break;
            default:
                $text = 'Unknown error.';
        }
        push_flash_message('error', $text);
    }
}

redirect_to('floppy.php');
