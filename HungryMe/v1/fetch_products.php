<?php
require_once '../includes/DbOperations.php';
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['restaurant'])) {
        $db = new DbOperations();
        $restaurant = $_GET['restaurant'];
        
        $result = $db->getItemsByRestaurant($restaurant);
        
        if (!empty($result)) {
            $response['error'] = false;
            $response['items'] = $result;
        } else {
            $response['error'] = true;
            $response['message'] = "No items found";
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Restaurant not specified";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Invalid request";
}

echo json_encode($response);
?>
