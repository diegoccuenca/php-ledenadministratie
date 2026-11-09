<?php

// Houd sessies veilig zonder HTTPS te vereisen op lokaal AMPPS.
ini_set('session.use_strict_mode', '1');
session_set_cookie_params([
    'httponly' => true,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
]);
session_start();

// Eén token per sessie voor alle formulieren (ook na het inloggen).
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require_once 'app/security.php';

$action = $_GET['action'] ?? '';
if (!isset($_SESSION['user']) && !in_array($action, ['showLogin', 'login', 'registreer'], true)) {
    header('Location: index.php?action=showLogin');
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', getenv('APP_DEBUG') === '1' ? '1' : '0'); // Alleen expliciet voor lokaal debuggen.
require_once 'app/controllers/FamilieController.php';
require_once 'app/controllers/FamilielidController.php';
require_once 'app/controllers/AuthController.php';

$action = $_GET['action'] ?? 'index';
if ($action === 'create') {
    (new FamilieController())->create();
} elseif ($action === 'store') {
    (new FamilieController())->store();
} elseif ($action === 'delete') {
    (new FamilieController())->delete();
} elseif ($action === 'edit') {
    (new FamilieController())->edit();
} elseif ($action === 'update') {
    (new FamilieController())->update();
} elseif ($action === 'familieleden') {
    $result = (new FamilielidController2())->index();
    require 'app/views/familieleden/index.php';
} elseif ($action === 'createFamilielid') {
    (new FamilielidController2())->create();
} elseif ($action === 'storeFamilielid') {
    (new FamilielidController2())->store();
} elseif ($action === 'deleteFamilielid') {
    (new FamilielidController2())->delete();
} elseif ($action === 'editFamilielid') {
    (new FamilielidController2())->edit();
} elseif ($action === 'updateFamilielid') {
    (new FamilielidController2())->update();
} elseif ($action === 'showLogin') {
    (new AuthController())->showLogin();
} elseif ($action === 'login') {
    (new AuthController())->login();
} elseif ($action === 'registreer') {
    (new AuthController())->registreerEersteGebruiker();
} elseif ($action === 'logout') {
    (new AuthController())->logout();
} else {
    $result = (new FamilieController())->index();
    require 'app/views/families/index.php';
}
