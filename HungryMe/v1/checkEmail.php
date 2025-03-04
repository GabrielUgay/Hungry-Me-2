<?php

require_once '../includes/DbOperations.php';
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $db = new DbOperations();
        $email = $_POST['email'];
        
        if ($db->checkEmailExists($email)) {
            $response['success'] = true;
            $response['message'] = "Email exists.";
        } else {
            $response['success'] = false;
            $response['message'] = "Email not found.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Required field missing.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request.";
}

echo json_encode($response);

?>
