<?php

require_once '../includes/DbOperations.php';
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['username'])) {
        $db = new DbOperations();  // Assuming you have a class for database operations
        $user_id = $db->getUserId($_GET['username']);

        if ($user_id) {
            $response['success'] = true;
            $response['user_id'] = $user_id;
        } else {
            $response['success'] = false;
            $response['message'] = "User not found.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Required fields missing.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request.";
}

echo json_encode($response);

?>
