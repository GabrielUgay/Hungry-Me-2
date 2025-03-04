<?php

require_once '../includes/DbOperations.php';
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id']) && isset($_POST['id']) && isset($_POST['quantity']) && isset($_POST['restaurant'])) {
        $db = new DbOperations();
        $userId = intval($_POST['user_id']);
        $cartItems = array();

        // Ensure all fields are arrays and have the same count
        if (is_array($_POST['id']) && is_array($_POST['quantity']) && is_array($_POST['restaurant']) && 
            count($_POST['id']) === count($_POST['quantity']) && count($_POST['id']) === count($_POST['restaurant'])) {
            
            for ($i = 0; $i < count($_POST['id']); $i++) {
                $cartItems[] = array(
                    'id' => intval($_POST['id'][$i]),
                    'quantity' => intval($_POST['quantity'][$i]),
                    'restaurant' => $_POST['restaurant'][$i]
                );
            }

            $result = $db->addCart($userId, $cartItems);

            if ($result['success']) {
                $response['success'] = true;
                $response['message'] = "Cart updated successfully.";
            } else {
                $response['success'] = false;
                $response['message'] = "Error updating cart: " . $result['message'];
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Invalid data format.";
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
