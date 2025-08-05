<?php
function isLoggedIn() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    return (isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])) ||
           (isset($_COOKIE['member_id']) && !empty($_COOKIE['member_id']));
}

function redirectIfLoggedIn($redirect_to = 'index.php') {
    if (isLoggedIn()) {
        header("Location: $redirect_to");
        exit();
    }
}

function redirectIfNotLoggedIn($redirect_to = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirect_to");
        exit();
    }
}
?>