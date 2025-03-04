<?php

require_once '../includes/DbOperations.php';
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['username']) &&
        isset($_POST['email']) &&
        isset($_POST['password']) &&
        isset($_POST['phone_number'])
    ) {
        $db = new DbOperations();
        $result = $db->createUser(
            $_POST['username'],
            $_POST['password'],
            $_POST['email'],
            $_POST['phone_number']
        );

        if ($result === true) {
            $response['error'] = false;
            $response['message'] = "User registered successfully";
        } elseif ($result === "exists") {
            $response['error'] = true;
            $response['message'] = "User already exists";
        } else {
            $response['error'] = true;
            $response['message'] = "Some error occurred. Please try again.";
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Invalid Request";
}

echo json_encode($response);

?>
