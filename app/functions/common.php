<?php

session_start();

function database_connection() {
    static $db = NULL;

    if ($db) {
        return $db;
    }

    try {
        return new PDO('mysql:dbname=floppier;host=127.0.0.1', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    } catch (PDOException $ex) {
        http_response_code(500);
        die;
    }
}

function push_flash_message($type, $text) {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][] = [
        'type' => $type,
        'text' => $text
    ];
}

function next_flash_message() {
    if (!isset($_SESSION['flash']) || count($_SESSION['flash']) < 1) {
        return FALSE;
    }
    return array_shift($_SESSION['flash']);
}

function redirect_to($url) {
    http_response_code(301);
    header('Location: ' . $url);
    die;
}

function continue_if_logged_in() {
    if (!isset($_SESSION['user'])) {
        push_flash_message('warning', 'Please login first.');
        redirect_to('login.php');
    }
}
