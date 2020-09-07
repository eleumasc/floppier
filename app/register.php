<?php

require_once('functions/common.php');

function register() {
    if (!isset($_POST['username']) ||
        !isset($_POST['password']) ||
        !isset($_POST['confirm-password'])) {
            return 1;
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
            
    if (strlen($username) < 1 ||
        strlen($username) > 50) {
        return 2;
    }

    if (!preg_match('/^[A-Za-z0-9]*$/', $username)) {
        return 3;
    }

    if (strlen($password) < 4) {
        return 4;
    }

    if ($password !== $confirm_password) {
        return 5;
    }
    
    $salt = substr(hash('sha256', uniqid()), 0, 8);
    $password = hash('sha256', $password . $salt);

    $db = database_connection();

    $stat = $db->prepare('INSERT INTO `users` (`username`, `password`, `salt`) VALUES (:username, :password, :salt)');

    try {
        $stat->execute([
            'username' => $username,
            'password' => $password,
            'salt' => $salt,
        ]);
    } catch (PDOException $ex) {
        if ($ex->getCode() === '23000') {
            return 6;
        }
        throw $ex;
    }

    return 0;
}

if (isset($_POST['register'])) {
    $result = register();
    if (!$result) {
        push_flash_message('success', 'The user has been registered successfully!');
        redirect_to('index.php');
    } else {
        switch ($result) {
            case 1:
                $text = 'Invalid form.';
            break;
            case 2:
                $text = 'The length of username must be between 1 and 50.';
            break;
            case 3:
                $text = 'The username must only include alphanumeric characters.';
            break;
            case 4:
                $text = 'The length of password must be greater or equal to 4.';
            break;
            case 5:
                $text = 'Password doesn\'t match.';
            break;
            case 6:
                $text = 'The username already exists.';
            break;
            default:
                $text = 'Unknown error.';
        }
        push_flash_message('error', $text);
        redirect_to('register.php');
    }
}

include('templates/register-page.php');
