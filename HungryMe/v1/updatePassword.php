<?php

require_once '../includes/DbOperations.php';
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['new_password'])) {
        $db = new DbOperations();
        $email = $_POST['email'];
        $newPassword = $_POST['new_password'];

        if ($db->updatePassword($email, $newPassword)) {
            $response['success'] = true;
            $response['message'] = "Password updated successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Failed to update password.";
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
