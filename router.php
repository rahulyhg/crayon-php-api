<?php
/*
error_reporting(-1);
ini_set('display_errors', 'On');
*/

require_once('utils/preflight-check.php');
require_once('utils/database-connection.php');

require_once('utils/jwt-config.php');

header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

$input = json_decode(file_get_contents('php://input'), true);
$dbc = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

$request = $_SERVER['PATH_INFO'];

switch ($request) {
    case '/login':
        require_once('routes/login.php');
        break;
    case '/validate-jwt':
        require_once('routes/validate-jwt.php');
        break;
    case '/upload':
        require_once('routes/upload.php');
        break;
    case '/users':
        require_once('routes/users.php');
        break;
    default:
        require_once('routes/api.php');
        break;
}

function error_response($msg) {
    return array('success' => false, 'msg' => $msg);
}
