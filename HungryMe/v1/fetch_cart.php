<?php
require_once '../includes/DbOperations.php';
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['user_id']) && isset($_GET['restaurant'])) {
        $db = new DbOperations();
        $user_id = $_GET['user_id'];
        $restaurant = $_GET['restaurant'];

        $result = $db->getItemsByCart($user_id, $restaurant);

        if (!$result['error'] && !empty($result['items'])) { // Check 'items' key
            $response['error'] = false;
            $response['items'] = $result['items'];
        } else {
            $response['error'] = true;
            $response['message'] = $result['message'] ?? "No items found"; // More precise error handling
        }
    } else {
        $response['error'] = true;
        $response['message'] = "User ID or restaurant not specified";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Invalid request";
}

echo json_encode($response);
?>
