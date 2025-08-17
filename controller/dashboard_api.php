<?php
require_once 'config.php';
require_once 'dashboardController.php';

$controller = new DashboardController($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;