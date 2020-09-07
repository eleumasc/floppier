<?php

require_once('functions/common.php');

function login() {
    if (!isset($_POST['username']) ||
        !isset($_POST['password'])) {
            return 1;
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $db = database_connection();

    $stat = $db->prepare('SELECT * FROM `users` WHERE `username` = :username');

    $stat->execute([
        'username' => $username,
    ]);

    $user = $stat->fetch();

    if (!$user) {
        return 2;
    }

    if (hash('sha256', $password . $user['salt']) !== $user['password']) {
        return 3;
    }

    $_SESSION['user'] = $user;

    return 0;
}

if (isset($_POST['login'])) {
    $result = login();
    if (!$result) {
        redirect_to('floppy.php');
    } else {
        push_flash_message('error', 'Wrong username/password.');
        redirect_to('login.php');
    }
}

include('templates/login-page.php');
