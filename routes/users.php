<?php
require_once('utils/authorize.php');

$input = json_decode(file_get_contents('php://input'), true);

$dbc = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

$id         = (int)$_GET['id'];
$name       = mysqli_real_escape_string($dbc, $input['name']);
$username   = mysqli_real_escape_string($dbc, $input['username']);
$password   = mysqli_real_escape_string($dbc, $input['password']);

$password_hashed = password_hash($password, PASSWORD_BCRYPT, array('cost' => 14));

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        $output = array();
        $query = 'select id, name, username From users';

		$result = mysqli_query($dbc, $query);

		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            array_push($output, $row);
        }

        header('Content-Type: application/json');

        echo json_encode(array(
            'users' => $output
        ));

        exit;
    case 'POST':
		$query = "insert users (name, username, password_hashed) values ('$name', '$username', '$password_hashed')";
		mysqli_query($dbc, $query);

		echo mysqli_insert_id($dbc);
		exit;
    case 'PUT':
        if (empty($id)) {
            http_response_code(404);
        }

		$query = "update users set name='$name', username='$username'";

		if (!empty($password)) {
    		$query .= ", password_hashed='$password_hashed'";
		}

		$query .= " where id=$id";

		mysqli_query($dbc, $query);

		echo 'ok';
		exit;
    case 'DELETE':
        if (empty($id)) {
            http_response_code(404);
        }

        $query = "delete from users where id=$id";
		mysqli_query($dbc, $query);

		echo 'ok';
		exit;
    default:
        http_response_code(401);
        die('Unsupported request method');
}
