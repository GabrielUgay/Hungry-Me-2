<?php

class DbOperations {
    private $con;

    function __construct() {
        require_once dirname(__FILE__).'/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    // Use this to create a new user:
    public function createUser($username, $pass, $email, $phone) {
        if ($this->isUserExists($email, $username)) {
            return "exists"; // Return "exists" if the user already exists
        }

        $password = md5($pass);
        $stmt = $this->con->prepare("
            INSERT INTO `users` (`username`, `email`, `password`, `phone`, `created_at`, `user_role`) 
            VALUES (?, ?, ?, ?, NOW(), 'user')
        ");
        $stmt->bind_param("ssss", $username, $email, $password, $phone);

        return $stmt->execute();
    }

    // Now use this to login:
    public function userLogin($username, $pass) {
        $password = md5($pass);
        $stmt = $this->con->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Get user by email:
    public function getUserByUsername($username) {
        $stmt = $this->con->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Function to check if an email exists
    public function checkEmailExists($email) {
        $stmt = $this->con->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Function to update the user's password
    public function updatePassword($email, $newPassword) {
        $hashedPassword = md5($newPassword);
        $stmt = $this->con->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        return $stmt->execute();
    }

    // Function to get items by restaurant
    public function getItemsByRestaurant($restaurant) {
        $stmt = $this->con->prepare("SELECT id, name, price, stock, category, restaurant, file FROM products WHERE restaurant = ?");
        $stmt->bind_param("s", $restaurant);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = array();

        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }


    // Do not change this at all:
    public function getUserId($username) {
        $stmt = $this->con->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id);

        if ($stmt->fetch()) {
            return $user_id;
        } else {
            return false;
        }
    }

    

    public function addCart($user_id, $product_id, $quantity, $restaurant) {
        $stmt = $this->con->prepare("INSERT INTO cart (user_id, product_id, quantity, created_at, restaurant) VALUES (?, ?, ?, NOW(), ?)");
        
        if (!$stmt) {
            return false; // Failed to prepare statement
        }

        $stmt->bind_param("iiis", $user_id, $product_id, $quantity, $restaurant);

        if ($stmt->execute()) {
            $stmt->close();
            return true; // Insertion successful
        } else {
            $stmt->close();
            return false; // Execution failed
        }
    }


    public function deleteCart($user_id, $restaurant) {
        $stmt = $this->con->prepare("DELETE FROM cart WHERE user_id = ? and restaurant = ?;");
        
        if (!$stmt) {
            return false; // Failed to prepare statement
        }

        $stmt->bind_param("is", $user_id, $restaurant);

        if ($stmt->execute()) {
            $stmt->close();
            return true; // Deletion successful
        } else {
            $stmt->close();
            return false; // Execution failed
        }
    }


    public function getItemsByCart($user_id, $restaurant) {
        $stmt = $this->con->prepare("
            SELECT p.name, p.price, c.quantity, p.file
            FROM products p
            JOIN cart c ON p.id = c.product_id
            WHERE c.user_id = ? AND c.restaurant = ?;
        ");

        if (!$stmt) {
            return array("error" => true, "message" => "Database query failed: " . $this->con->error);
        }

        $stmt->bind_param("is", $user_id, $restaurant); // Change "is" to "ii" if restaurant is an ID
        $stmt->execute();
        $result = $stmt->get_result();

        $items = array();
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        $stmt->close();

        if (empty($items)) {
            return array("error" => true, "message" => "No items found");
        }

        return array("error" => false, "items" => $items);
    }



    // Use this to check if user exists:
    private function isUserExists($email, $username) {
        $stmt = $this->con->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();
        
        return $stmt->num_rows > 0;
    }
}

?>
