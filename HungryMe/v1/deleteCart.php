<?php

require_once '../includes/DbOperations.php';
header('Content-Type: application/json');
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id']) and isset($_POST['restaurant'])) {
        $db = new DbOperations();
        $result = $db->deleteCart($_POST['user_id'], $_POST['restaurant']); 

        if ($result) {
            $response['success'] = true;
            $response['message'] = "Cart deleted successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error: No rows deleted. Maybe the user_id does not exist?";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Invalid request. Missing user_id.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);


?>