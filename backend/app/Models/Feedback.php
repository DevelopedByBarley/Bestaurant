<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

class Feedback extends Admin
{
    public function store($feedback, $content, $ip)
    {
        try {
            $stmt = $this->Pdo->prepare("INSERT INTO `feedbacks` VALUES (NULL, :user_ip, :feedback, :content, current_timestamp());");
            $stmt->bindParam(":user_ip", $ip);
            $stmt->bindParam(":feedback", $feedback);
            $stmt->bindParam(":content", $content);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("An error occurred during the database operation in the store method at Feedback model: " . $e->getMessage());
        }
    }
}
