<?php

require_once '../includes/DbOperations.php';
header('Content-Type: application/json');
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id']) && isset($_POST['product_id']) && isset($_POST['quantity']) && isset($_POST['restaurant'])) {
        $db = new DbOperations();
        $result = $db->addCart($_POST['user_id'], $_POST['product_id'], $_POST['quantity'], $_POST['restaurant']); 

        if ($result) {
            $response['success'] = true;
            $response['message'] = "Cart item added successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Failed to insert new cart item.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Invalid request. Missing required fields.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);


?>
