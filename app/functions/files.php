<?php

require_once('common.php');

define('FLOPPY_DISK_CAPACITY', 1474560);

function get_files_by_user($user) {
    $db = database_connection();

    $stat = $db->prepare('SELECT * FROM `files` WHERE `owner` = :owner');

    $stat->execute([
        'owner' => $user,
    ]);

    return $stat->fetchAll();
}

function get_file_by_id($id) {
    $db = database_connection();

    $stat = $db->prepare('SELECT * FROM `files` WHERE `id` = :id');

    $stat->execute([
        'id' => $id,
    ]);

    return $stat->fetch();
}

function get_used_space_by_user($user) {
    $db = database_connection();

    $stat = $db->prepare('SELECT SUM(`size`) AS `used_space` FROM `files` WHERE `owner` = :owner');

    $stat->execute([
        'owner' => $user,
    ]);

    return (int)$stat->fetch()['used_space'];
}

function there_is_enough_space($user, $file) {
    return (FLOPPY_DISK_CAPACITY - (get_used_space_by_user($user) + $file['size']) >= 0);
}

function calculate_file_hash($file_path) {
	$output = shell_exec('md5sum ' . $file_path);
	return substr($output, 0, strpos($output, ' '));
}
